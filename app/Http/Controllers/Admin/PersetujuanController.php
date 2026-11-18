<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\Inovasi;
use App\Models\Pegawai;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PersetujuanController extends Controller
{
    /** Tipe konten yang mendukung workflow persetujuan. */
    private const ELOQUENT_TYPES = [
        'pengumuman' => Pengumuman::class,
        'inovasi' => Inovasi::class,
        'dokumen' => Dokumen::class,
        'pegawai' => Pegawai::class,
    ];

    /** Submenu Persetujuan Konten. */
    private const SUBMENUS = [
        'semua' => 'Semua',
        'pengumuman' => 'Pengumuman',
        'dokumen' => 'Dokumen',
        'pegawai' => 'Pegawai',
    ];

    public function index(Request $request)
    {
        abort_if(! auth()->user()->isSuperadmin(), 403, 'Hanya Kepala Bappedalitbang yang dapat memeriksa persetujuan.');

        $typeFilter = $request->get('type', 'semua');
        $submenus = self::SUBMENUS;
        $items = collect();

        foreach (self::ELOQUENT_TYPES as $type => $class) {
            if ($typeFilter !== 'semua' && $typeFilter !== $type) {
                continue;
            }

            $class::pending()->with(['bidang', 'submittedBy'])->get()->each(function ($r) use (&$items, $type) {
                $items->push($this->toListItem(
                    $type,
                    $r->id,
                    $r->judul ?? $r->nama ?? '—',
                    $r->bidang?->singkatan ?? $r->bidang?->nama ?? '—',
                    optional($r->submittedBy)->name,
                    $r->submitted_at ?? $r->created_at,
                    $r->statusLabel()
                ));
            });
        }

        if ($typeFilter === 'semua' || $typeFilter === 'artikel') {
            DB::table('artikel as a')
                ->leftJoin('users as u', 'u.id', '=', 'a.user_id')
                ->leftJoin('bidang as b', 'b.id', '=', 'a.bidang_id')
                ->where('a.status', 'pending')
                ->whereNull('a.deleted_at')
                ->select('a.artikel_id', 'a.judul', 'a.submitted_at', 'a.created_at', 'b.singkatan as bidang', 'u.name as submitter')
                ->get()
                ->each(function ($r) use (&$items) {
                    $items->push($this->toListItem(
                        'artikel',
                        $r->artikel_id,
                        $r->judul,
                        $r->bidang ?? '—',
                        $r->submitter,
                        $r->submitted_at ?? $r->created_at,
                        'Menunggu Persetujuan'
                    ));
                });
        }

        $items = $items->sortByDesc('tanggal')->values();

        return view('admin.persetujuan.index', compact('items', 'typeFilter', 'submenus'));
    }

    public function show($type, $id)
    {
        abort_if(! auth()->user()->isSuperadmin(), 403, 'Hanya Kepala Bappedalitbang yang dapat memeriksa persetujuan.');

        if (array_key_exists($type, self::ELOQUENT_TYPES)) {
            $model = (self::ELOQUENT_TYPES[$type])::with(['bidang', 'submittedBy', 'approvedBy'])->findOrFail($id);
            $item = $this->resolveModel($type, $model);
            $module = config('cms.modules')[$type] ?? null;

            return view('admin.persetujuan.show', compact('type', 'item', 'model', 'module'));
        }

        if ($type === 'artikel') {
            $item = $this->resolveArtikel($id);
            abort_if(! $item, 404);

            $video = null;
            if (! empty($item->video_id)) {
                $video = DB::table('galeri_video')->where('id', $item->video_id)->where('enabled', true)->first();
            }

            return view('admin.persetujuan.show', compact('type', 'item', 'video'));
        }

        abort(404);
    }

    public function approve($type, $id)
    {
        abort_if(! auth()->user()->isSuperadmin(), 403);

        $user = auth()->user();

        if (array_key_exists($type, self::ELOQUENT_TYPES)) {
            $record = (self::ELOQUENT_TYPES[$type])::findOrFail($id);
            $data = [
                'status' => 'published',
                'approved_by' => $user->id,
                'approved_at' => now(),
                'rejection_reason' => null,
            ];

            if (Schema::hasColumn($record->getTable(), 'published_at')) {
                $data['published_at'] = now();
            }

            if ($type === 'pegawai') {
                $data['pending_changes'] = null;
            }

            $record->update($data);
        } elseif ($type === 'artikel') {
            DB::table('artikel')->where('artikel_id', $id)->update(['status' => 'published']);
        } else {
            abort(404);
        }

        return redirect()->route('admin.persetujuan.index')
            ->with('flash_success', 'Konten disetujui dan telah tampil di halaman publik.');
    }

    public function reject(Request $request, $type, $id)
    {
        abort_if(! auth()->user()->isSuperadmin(), 403);

        $reason = trim((string) $request->input('reason'));

        if (array_key_exists($type, self::ELOQUENT_TYPES)) {
            $record = (self::ELOQUENT_TYPES[$type])::findOrFail($id);

            // Pegawai: kembalikan ke data aktif sebelum perubahan (rollback staging).
            if ($type === 'pegawai' && is_array($record->pending_changes) && $record->pending_changes !== []) {
                $record->update(array_merge($record->pending_changes, [
                    'status' => 'published',
                    'rejection_reason' => $reason,
                    'pending_changes' => null,
                ]));
            } else {
                $record->update(['status' => 'rejected', 'rejection_reason' => $reason]);
            }
        } elseif ($type === 'artikel') {
            DB::table('artikel')->where('artikel_id', $id)->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
            ]);
        } else {
            abort(404);
        }

        return redirect()->route('admin.persetujuan.index')
            ->with('flash_success', 'Konten ditolak. Admin bidang dapat melihat alasan penolakan.');
    }

    public function revision(Request $request, $type, $id)
    {
        abort_if(! auth()->user()->isSuperadmin(), 403);

        $reason = trim((string) $request->input('reason'));

        if (array_key_exists($type, self::ELOQUENT_TYPES)) {
            $record = (self::ELOQUENT_TYPES[$type])::findOrFail($id);
            $record->update([
                'status' => 'revision',
                'rejection_reason' => $reason,
                'pending_changes' => null,
            ]);
        } elseif ($type === 'artikel') {
            DB::table('artikel')->where('artikel_id', $id)->update([
                'status' => 'revision',
                'rejection_reason' => $reason,
            ]);
        } else {
            abort(404);
        }

        return redirect()->route('admin.persetujuan.index')
            ->with('flash_success', 'Konten dikembalikan untuk diperbaiki oleh admin bidang.');
    }

    private function resolveModel($type, $record)
    {
        $base = [
            'type' => $type,
            'id' => $record->id,
            'judul' => $record->judul ?? $record->nama ?? '—',
            'isi' => $record->isi ?? $record->deskripsi ?? null,
            'status' => $record->status,
            'statusLabel' => $record->statusLabel(),
            'bidang' => optional($record->bidang)->nama,
            'bidangSingkatan' => optional($record->bidang)->singkatan,
            'submitter' => optional($record->submittedBy)->name,
            'approvedBy' => optional($record->approvedBy)->name,
            'submittedAt' => $record->submitted_at,
            'createdAt' => $record->created_at,
            'rejectionReason' => $record->rejection_reason,
            'detailFields' => [],
            'pendingChanges' => null,
        ];

        if ($type === 'dokumen') {
            $base['detailFields'] = [
                'Kategori' => Dokumen::CATEGORIES[$record->kategori] ?? $record->kategori,
                'Tahun / Periode' => $record->tahun,
                'File' => $record->file
                    ? '<a href="' . media_url($record->file) . '" target="_blank" class="text-accent underline">' . e(basename($record->file)) . '</a>'
                    : '—',
            ];
        }

        if ($type === 'pegawai') {
            $bidangField = collect(config('cms.modules.pegawai.fields'))->firstWhere('name', 'bidang_id');
            $bidangNama = $bidangField['options'][(string) $record->bidang_id] ?? '—';
            $base['detailFields'] = [
                'NIP' => $record->nip ?: '—',
                'Jabatan' => $record->jabatan ?: '—',
                'Bagian' => $record->bagian ?: '—',
                'Bidang' => $bidangNama,
                'Email' => $record->email ?: '—',
            ];
            $base['pendingChanges'] = $record->pending_changes;
        }

        return (object) $base;
    }

    private function resolveArtikel($id)
    {
        $row = DB::table('artikel as a')
            ->leftJoin('users as u', 'u.id', '=', 'a.user_id')
            ->leftJoin('bidang as b', 'b.id', '=', 'a.bidang_id')
            ->where('a.artikel_id', $id)
            ->whereNull('a.deleted_at')
            ->select('a.*', 'u.name as submitter', 'b.nama as bidang', 'b.singkatan as bidangSingkatan')
            ->first();

        if (! $row) {
            return null;
        }

        return (object) [
            'type' => 'artikel',
            'id' => $row->artikel_id,
            'judul' => $row->judul,
            'isi' => $row->isi,
            'status' => $row->status,
            'statusLabel' => match ($row->status) {
                'pending' => 'Menunggu Persetujuan',
                'rejected' => 'Ditolak',
                'revision' => 'Perlu Perbaikan',
                'published' => 'Disetujui',
                default => $row->status,
            },
            'bidang' => $row->bidang,
            'bidangSingkatan' => $row->bidangSingkatan,
            'submitter' => $row->submitter,
            'approvedBy' => null,
            'submittedAt' => $row->submitted_at,
            'createdAt' => $row->created_at,
            'rejectionReason' => $row->rejection_reason,
            'detailFields' => [],
            'pendingChanges' => null,
        ];
    }

    private function toListItem($type, $id, $judul, $bidang, $submitter, $tanggal, $statusLabel)
    {
        return (object) [
            'type' => $type,
            'id' => $id,
            'judul' => $judul,
            'bidang' => $bidang,
            'submitter' => $submitter,
            'tanggal' => $tanggal,
            'statusLabel' => $statusLabel,
            'url_show' => route('admin.persetujuan.show', [$type, $id]),
        ];
    }
}
