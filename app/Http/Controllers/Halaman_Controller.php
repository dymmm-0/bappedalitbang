<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Dokumen;
use App\Models\Faq;
use App\Models\Galeri;
use App\Models\GaleriVideo;
use App\Models\Inovasi;
use App\Models\MaklumatPelayanan;
use App\Models\Pegawai;
use App\Models\Penelitian;
use App\Models\Pengumuman;
use App\Models\Struktur;
use App\Models\TugasFungsi;
use Illuminate\Http\Request;

class Halaman_Controller extends Controller
{
    public function struktur()
    {
        $struktur = Struktur::first();

        return view('halaman.struktur', compact('struktur'));
    }

    public function tugasFungsi()
    {
        $tugasFungsi = TugasFungsi::first();

        return view('halaman.tugas_fungsi', compact('tugasFungsi'));
    }

    public function maklumatPelayanan()
    {
        $maklumat = MaklumatPelayanan::first();

        return view('halaman.maklumat_pelayanan', compact('maklumat'));
    }

    public function pegawai(Request $request)
    {
        $bidangList = Bidang::where('enabled', true)->ordered()->get()
            ->sortBy(function ($b) {
                $order = ['Sekretariat', 'PPEPD', 'PPM', 'Litbang', 'PSDA', 'IKW'];
                $i = array_search($b->singkatan, $order);

                return $i === false ? count($order) : $i;
            })
            ->values();

        $counts = Pegawai::query()
            ->where('enabled', true)
            ->select('bidang_id', \DB::raw('count(*) as total'))
            ->groupBy('bidang_id')
            ->pluck('total', 'bidang_id');

        $selected = null;

        if ($request->filled('bidang')) {
            $selected = $request->get('bidang') === 'lainnya'
                ? (object) ['id' => 'lainnya', 'nama' => 'Lainnya', 'singkatan' => 'Lainnya']
                : $bidangList->firstWhere('id', (int) $request->get('bidang'));
        }

        $isKepala = $request->boolean('kepala');

        $pegawai = Pegawai::where('enabled', true)
            ->when(! $isKepala && $selected, function ($q) use ($selected) {
                if ($selected->id === 'lainnya') {
                    $q->whereNull('bidang_id');
                } else {
                    $q->where('bidang_id', $selected->id);
                }
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = trim($request->get('q'));
                $q->where(function ($b) use ($term) {
                    $b->where('nama', 'like', "%{$term}%")
                        ->orWhere('jabatan', 'like', "%{$term}%")
                        ->orWhere('bagian', 'like', "%{$term}%");
                });
            })
            ->ordered()
            ->get()
            ->when($isKepala, fn ($c) => $c->filter(fn ($p) => $p->kategori['leader'])->values())
            // Hierarki visual mengikuti struktur organisasi (rank kategori), lalu urutan manual
            ->sortBy(fn ($p) => [$p->kategori['rank'], (int) $p->urutan])
            ->values();

        $perPage = 12;
        $page = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $pegawai = new \Illuminate\Pagination\LengthAwarePaginator(
            $pegawai->forPage($page, $perPage),
            $pegawai->count(),
            $perPage,
            $page,
            ['path' => url('profil/pegawai'), 'query' => $request->query()]
        );

        return view('halaman.pegawai', compact('pegawai', 'bidangList', 'counts', 'selected', 'isKepala'));
    }

    public function bidang()
    {
        $bidangList = Bidang::with('profil')->where('enabled', true)->ordered()->get();

        return view('halaman.bidang', compact('bidangList'));
    }

    public function bidangDetail($id)
    {
        $bidang = Bidang::with('profil')->findOrFail($id);
        $lainnya = Bidang::where('enabled', true)->whereKeyNot($bidang->getKey())->ordered()->get();

        $profil = $bidang->profil;
        $agenda = Pengumuman::published()
            ->where('jenis', 'agenda')
            ->where('bidang_id', $bidang->getKey())
            ->where(fn ($q) => $q->whereNull('selesai')->orWhere('selesai', '>=', now()))
            ->orderBy('mulai')
            ->limit(10)
            ->get();
        $inovasi = Inovasi::published()->where('bidang_id', $bidang->getKey())->latest('id')->limit(6)->get();
        $dokumen = Dokumen::where('enabled', true)->where('bidang_id', $bidang->getKey())->ordered()->limit(10)->get();

        return view('halaman.bidang_detail', compact(
            'bidang', 'profil', 'lainnya', 'agenda', 'inovasi', 'dokumen'
        ));
    }

    public function pengumuman(Request $request)
    {
        $bulan = $request->get('bulan');

        if (preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
            $pengumuman = Pengumuman::published()
                ->whereYear('tanggal', substr($bulan, 0, 4))
                ->whereMonth('tanggal', substr($bulan, 5, 2))
                ->latest('tanggal')
                ->paginate(10)
                ->withQueryString();

            return view('halaman.pengumuman', compact('pengumuman', 'bulan'));
        }

        $bulanList = Pengumuman::published()
            ->whereNotNull('tanggal')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as ym, COUNT(*) as jml")
            ->groupBy('ym')
            ->orderByDesc('ym')
            ->get();

        return view('halaman.pengumuman', compact('bulanList'));
    }

    public function pengumumanDetail($id)
    {
        $pengumuman = Pengumuman::published()->findOrFail($id);
        $lainnya = Pengumuman::published()->whereKeyNot($pengumuman->getKey())->latest('tanggal')->limit(5)->get();

        return view('halaman.pengumuman_detail', compact('pengumuman', 'lainnya'));
    }

    public function penelitian()
    {
        $penelitian = Penelitian::published()
            ->when(request()->filled('jenis'), fn ($q) => $q->where('jenis', request()->get('jenis')))
            ->latest('tahun')
            ->paginate(9)
            ->withQueryString();
        $jenisList = Penelitian::published()->pluck('jenis')->filter()->unique()->values();

        return view('halaman.penelitian', compact('penelitian', 'jenisList'));
    }

    public function penelitianDetail($id)
    {
        $penelitian = Penelitian::published()->findOrFail($id);
        $lainnya = Penelitian::published()->whereKeyNot($penelitian->getKey())->latest('tahun')->limit(3)->get();

        return view('halaman.penelitian_detail', compact('penelitian', 'lainnya'));
    }

    public function dokumen(Request $request)
    {
        $kategori = $request->get('kategori');
        $tahun = $request->get('tahun');
        $q = trim((string) $request->get('q'));

        // Mode pencarian: q mencakup judul, deskripsi, kategori, tahun + filter kategori/tahun.
        if ($q !== '') {
            $term = '%' . $q . '%';
            $dokumen = Dokumen::where('enabled', true)
                ->where(fn ($w) => $w->where('judul', 'like', $term)
                    ->orWhere('deskripsi', 'like', $term)
                    ->orWhere('kategori', 'like', $term)
                    ->orWhere('tahun', 'like', $term))
                ->when($request->filled('kategori'), fn ($qq) => $qq->where('kategori', $kategori))
                ->when($request->filled('tahun'), fn ($qq) => $qq->where('tahun', $tahun))
                ->orderByDesc('tahun')
                ->ordered()
                ->paginate(12)
                ->withQueryString();

            return view('halaman.dokumen_cari', [
                'dokumen' => $dokumen,
                'q' => $q,
                'aktifKategori' => $kategori,
                'aktifTahun' => $tahun,
                'tahunList' => $this->dokumenTahunList(),
            ]);
        }

        // Mode telusur: kategori → tahun/periode → daftar dokumen.
        if ($kategori) {
            abort_unless(array_key_exists($kategori, Dokumen::CATEGORIES), 404);

            if ($tahun) {
                $dokumen = Dokumen::where('enabled', true)
                    ->where('kategori', $kategori)
                    ->when($tahun === 'lainnya',
                        fn ($qq) => $qq->whereNull('tahun'),
                        fn ($qq) => $qq->where('tahun', $tahun))
                    ->orderByDesc('tahun')
                    ->ordered()
                    ->paginate(12)
                    ->withQueryString();

                return view('halaman.dokumen_daftar', [
                    'dokumen' => $dokumen,
                    'kategori' => $kategori,
                    'tahun' => $tahun,
                    'q' => $q,
                    'tahunList' => $this->dokumenTahunList(),
                ]);
            }

            $tahunList = Dokumen::where('enabled', true)
                ->where('kategori', $kategori)
                ->selectRaw('tahun, count(*) as jumlah')
                ->groupBy('tahun')
                ->orderByDesc('tahun')
                ->get();

            return view('halaman.dokumen_kategori', compact('kategori', 'tahunList'));
        }

        // Index: kartu 4 kategori utama.
        $kategoriCount = Dokumen::where('enabled', true)
            ->selectRaw('kategori, count(*) as jumlah')
            ->groupBy('kategori')
            ->pluck('jumlah', 'kategori');

        return view('halaman.dokumen', compact('kategoriCount'));
    }

    private function dokumenTahunList()
    {
        return Dokumen::where('enabled', true)
            ->whereNotNull('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');
    }

    public function galeri(Request $request)
    {
        $galeri = Galeri::where('enabled', true)
            ->when($request->filled('album'), fn ($q) => $q->where('album', $request->get('album')))
            ->ordered()
            ->paginate(12)
            ->withQueryString();
        $albumList = Galeri::where('enabled', true)->pluck('album')->filter()->unique()->values();
        $video = GaleriVideo::where('enabled', true)->ordered()->get();

        return view('halaman.galeri', compact('galeri', 'albumList', 'video'));
    }

    public function faq()
    {
        $faqs = Faq::where('enabled', true)->ordered()->get();

        return view('halaman.faq', compact('faqs'));
    }

    public function alamat()
    {
        return view('halaman.alamat');
    }
}
