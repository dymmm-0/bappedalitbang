<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

/**
 * Audit CRUD: setiap modul config/cms.php harus lolos store -> update -> destroy.
 * Tanpa RefreshDatabase supaya DB produksi tidak disentuh (pakai data dummy + hapus sesudahnya).
 * ponytail: sekali jalankan untuk audit; jangan jadikan suite rutin.
 */
class CrudAuditTest extends TestCase
{
    use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('email', 'admin.kepala@admin.com')->firstOrFail();
        $this->actingAs($user);
    }

    public function test_all_crud_modules_pass_store_update_destroy(): void
    {
        $modules = config('cms.modules');

        // field default per tipe (dummy yang lolos validasi)
        $dummy = [
            'text' => 'Data Audit ' . uniqid(),
            'textarea' => 'Isi textarea audit.',
            'editor' => '<p>Isi editor audit.</p>',
            'repeater' => ['baris satu', 'baris dua'],
            'select' => null,  // isi di bawah
            'checkbox' => '1',
            'number' => '42',
            'url' => 'https://contoh.test',
            'date' => '2026-08-20',
            'datetime' => '2026-08-20T09:00',
            'image' => null,
            'file' => null,
        ];

        $results = [];
        foreach ($modules as $slug => $module) {
            $data = [];
            $needsUpload = false;
            foreach ($module['fields'] as $field) {
                $name = $field['name'];
                $type = $field['type'];

                if ($type === 'select') {
                    $opts = $field['options'] ?? [];
                    $values = array_keys($opts);
                    $data[$name] = count($values) > 1 ? $values[1] : ($values[0] ?? '');
                } elseif ($type === 'image' || $type === 'file') {
                    if ($field['required'] ?? false) {
                        $needsUpload = true;
                    }
                    continue; // skip upload di audit ini
                } else {
                    $data[$name] = $dummy[$type] ?? '';
                }
            }

            if ($needsUpload) {
                $results[$slug] = 'skip (butuh upload)';
                continue;
            }

            try {
                // Batasi semua operasi HANYA pada baris yang dibuat test ini
                // (id > max id sebelum store) agar data produksi tak disentuh.
                $modelClass = $module['model'];
                $startId = (int) (new $modelClass)->newQuery()->max('id');

                // --- store ---
                if ($module['singleton'] ?? false) {
                    // Singleton = baris ASLI situs (Visi & Misi, Struktur, dst).
                    // Hanya verifikasi halaman bisa dimuat; JANGAN pernah menulis
                    // data dummy ke baris asli agar konten produksi tidak rusak.
                    $res = $this->get("/admin/crud/{$slug}");
                    $state = $res->getStatusCode() === 200 ? 'singleton-view-ok' : 'singleton-FAIL(' . $res->getStatusCode() . ')';
                    $results[$slug] = $state;
                    continue;
                }

                $res = $this->post("/admin/crud/{$slug}", $data);
                if ($res->getStatusCode() !== 302) {
                    $results[$slug] = 'store-FAIL(' . $res->getStatusCode() . '): ' . $this->firstError($res);
                    continue;
                }

                $record = (new $modelClass)->newQuery()->where('id', '>', $startId)->latest('id')->first();
                if (! $record) {
                    $results[$slug] = 'store-FAIL: tidak ada record baru';
                    continue;
                }
                $id = $record->id;

                // --- update ---
                $res = $this->put("/admin/crud/{$slug}/{$id}", $data);
                if ($res->getStatusCode() !== 302) {
                    $results[$slug] = 'update-FAIL(' . $res->getStatusCode() . '): ' . $this->firstError($res);
                    continue;
                }

                // --- destroy ---
                $res = $this->delete("/admin/crud/{$slug}/{$id}");
                if ($res->getStatusCode() !== 302) {
                    $results[$slug] = 'destroy-FAIL(' . $res->getStatusCode() . ')';
                    continue;
                }

                $results[$slug] = 'ok';
            } catch (\Throwable $e) {
                $results[$slug] = 'EXCEPTION: ' . get_class($e) . ': ' . substr($e->getMessage(), 0, 200);
            } finally {
                // Bersihkan SEMUA baris hasil audit modul ini (bisa soft-delete
                // karena destroy; hapus permanen agar tidak menyisakan sampah).
                $this->cleanupAuditRows($module['model'], $startId);
            }
        }

        $fails = array_filter($results, fn ($r) => !in_array($r, ['ok', 'singleton-view-ok', 'skip (butuh upload)'], true));
        echo "\n=== HASIL AUDIT CRUD ===\n";
        foreach ($results as $slug => $r) {
            echo str_pad($slug, 22) . ' ' . $r . "\n";
        }

        $this->assertSame([], $fails, 'Modul gagal: ' . json_encode($fails));
    }

    /**
     * Hapus permanen seluruh baris dengan id > startId milik model modul.
     * newQueryWithoutScopes() menyertakan baris yang sudah soft-delete,
     * tanpa bergantung pada macro `withTrashed`/state boot model lain.
     */
    private function cleanupAuditRows(string $modelClass, int $startId): void
    {
        $model = new $modelClass;

        $model->newQueryWithoutScopes()
            ->where('id', '>', $startId)
            ->get()
            ->each(function ($row) {
                if (method_exists($row, 'forceDelete')) {
                    $row->forceDelete();
                } else {
                    $row->delete();
                }
            });
    }

    private function firstError($res): string
    {
        $errors = session('errors');
        if ($errors) {
            return implode('; ', $errors->all());
        }

        return (string) $res->getContent();
    }
}