<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class Crud_Controller extends Controller
{
    /**
     * Resolve the module config for the current route.
     */
    protected function module(): array
    {
        $slug = request()->route('module');
        $modules = config('cms.modules');

        abort_if(! is_string($slug) || ! array_key_exists($slug, $modules), 404, 'Modul tidak dikenal.');

        return $modules[$slug];
    }

    protected function moduleSlug(): string
    {
        return (string) request()->route('module');
    }

    protected function authorizeModule(): void
    {
        abort_if(auth()->user()->cannot('manage_' . $this->moduleSlug()), 403, 'Anda tidak memiliki izin untuk modul ini.');
    }

    /**
     * Terapkan kebijakan status konten (workflow persetujuan):
     *
     * - Kepala Bappedalitbang / Super Admin: berwenang menetapkan status
     *   akhir (published / rejected / revision / draft / pending) dan
     *   mencatat approved_by + rejection_reason.
     * - Admin bidang: HANYA boleh draft atau pending. Status
     *   published/rejected/revision ditolak (403) agar konten tidak bisa
     *   langsung tampil ke publik tanpa persetujuan.
     *
     * Kolom submitted_by/submitted_at/approved_by/rejection_reason hanya
     * diisi bila model memang memilikinya.
     */
    protected function applyApprovalPolicy(array &$data, $model): void
    {
        if (! array_key_exists('status', $data)) {
            return;
        }

        $user = auth()->user();
        $hasApprovalCols = \Schema::hasColumn($model->getTable(), 'submitted_by');
        $status = (string) $data['status'];

        if ($user->isSuperadmin()) {
            if ($status === 'published') {
                $data['approved_by'] = $user->id;
                $data['approved_at'] = $data['approved_at'] ?? now();
                $data['rejection_reason'] = null;
            } elseif (in_array($status, ['rejected', 'revision'], true)) {
                $data['rejection_reason'] = request('rejection_reason') ?? $data['rejection_reason'] ?? null;
            }

            return;
        }

        if (! in_array($status, ['draft', 'pending'], true)) {
            abort(403, 'Status "' . $status . '" hanya dapat ditetapkan oleh Kepala Bappedalitbang. Pengajuan Anda akan masuk sebagai "Menunggu Persetujuan".');
        }

        if ($hasApprovalCols && $status === 'pending') {
            $data['submitted_by'] = $user->id;
            $data['submitted_at'] = $data['submitted_at'] ?? now();
            $data['rejection_reason'] = null;
        }
    }

    protected function modelInstance(array $module)
    {
        $class = $module['model'];

        return new $class;
    }

    protected function upload(Request $request, array $field, ?string $current): ?string
    {
        if (! $request->hasFile($field['name'])) {
            return $current;
        }

        $file = $request->file($field['name']);
        $stored = store_upload($file, $this->moduleSlug() . '/' . date('Y'));

        if ($current && File::exists(public_path('uploads/' . $current))) {
            File::delete(public_path('uploads/' . $current));
        }

        return $stored;
    }

    protected function valueForField(Request $request, array $module, array $field, ?array $record = null): mixed
    {
        $name = $field['name'];
        $current = $record[$name] ?? null;

        return match ($field['type']) {
            'image', 'file' => $this->upload($request, $field, $current),
            'repeater' => $this->repeaterToArray($request->input($name)),
            'checkbox' => $request->boolean($name),
            'editor', 'textarea' => (string) ($request->input($name) ?? ''),
            'number', 'date', 'datetime' => $request->input($name),
            default => (string) ($request->input($name) ?? ''),
        };
    }

    protected function repeaterToArray($value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map('trim', $value), fn ($v) => $v !== ''));
        }

        if (is_string($value)) {
            $lines = preg_split('/\r?\n/', $value);
            $lines = array_map('trim', $lines);

            return array_values(array_filter($lines, fn ($v) => $v !== ''));
        }

        return [];
    }

    protected function validation(array $module): array
    {
        $rules = [];
        $action = request()->route()->getActionMethod();
        $isUpdate = $action === 'update' || $action === 'singletonStore';

        $imageMimes = implode(',', (array) config('uploads.allowed_image_extensions', ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']));
        $fileMimes  = implode(',', (array) config('uploads.allowed_file_extensions', []));

        foreach ($module['fields'] as $field) {
            $rule = null;

            if ($field['required'] ?? false) {
                $rule = 'required';

                if ($field['type'] === 'image') {
                    $rule = $isUpdate ? 'sometimes|image|mimes:' . $imageMimes . '|max:20480' : 'required|image|mimes:' . $imageMimes . '|max:20480';
                } elseif ($field['type'] === 'file') {
                    $rule = $isUpdate ? 'sometimes|file|mimes:' . $fileMimes . '|max:20480' : 'required|file|mimes:' . $fileMimes . '|max:20480';
                } elseif ($field['type'] === 'url') {
                    $rule = 'required|url';
                }
            } elseif ($field['type'] === 'image') {
                $rule = 'nullable|image|mimes:' . $imageMimes . '|max:20480';
            } elseif ($field['type'] === 'file') {
                $rule = 'nullable|file|mimes:' . $fileMimes . '|max:20480';
            } elseif ($field['type'] === 'url') {
                $rule = 'nullable|url';
            } elseif ($field['type'] === 'number') {
                $rule = 'nullable|integer';
            }

            if ($rule) {
                $rules[$field['name']] = $rule;
            }
        }

        return $rules;
    }

    public function index(Request $request)
    {
        $this->authorizeModule();

        $module = $this->module();
        $slug = $this->moduleSlug();

        if ($module['singleton'] ?? false) {
            $model = $this->modelInstance($module);
            $record = $model->first();

            return view('admin.crud.form', compact('module', 'slug', 'record'));
        }

        $model = $this->modelInstance($module);

        $query = $model->newQuery();

        if ($request->filled('q') && ! empty($module['searchable'])) {
            $q = trim($request->get('q'));
            $query->where(function ($builder) use ($module, $q) {
                foreach ($module['searchable'] as $i => $column) {
                    $builder->where($column, 'like', "%{$q}%");
                }
            });
        }

        if (($module['sortable'] ?? false)) {
            $query->orderBy('urutan')->orderBy('id');
        } else {
            $query->latest('id');
        }

        $records = $query->paginate(20)->withQueryString();

        return view('admin.crud.index', compact('module', 'slug', 'records'));
    }

    public function create()
    {
        $this->authorizeModule();

        $module = $this->module();
        $slug = $this->moduleSlug();

        return view('admin.crud.form', compact('module', 'slug'))->with('record', null);
    }

    public function store(Request $request)
    {
        $this->authorizeModule();

        $module = $this->module();
        $slug = $this->moduleSlug();

        if ($module['singleton'] ?? false) {
            return $this->singletonStore($request, $module, $slug);
        }

        $validator = Validator::make($request->all(), $this->validation($module));
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $model = $this->modelInstance($module);
        $data = [];

        foreach ($module['fields'] as $field) {
            $data[$field['name']] = $this->valueForField($request, $module, $field);
        }

        $this->applyApprovalPolicy($data, $model);

        $record = $model->create($data);

        if (method_exists($record, 'createRevision')) {
            $record->createRevision('Dibuat');
        }

        return redirect()->route('admin.crud.index', $slug)
            ->with('flash_success', 'Data berhasil disimpan.');
    }

    public function edit(Request $request, $module, $id)
    {
        $this->authorizeModule();

        $module = $this->module();
        $slug = $this->moduleSlug();
        $model = $this->modelInstance($module);

        $record = $model->findOrFail($id);

        return view('admin.crud.form', compact('module', 'slug', 'record'));
    }

    public function update(Request $request, $module, $id)
    {
        $this->authorizeModule();

        $module = $this->module();
        $slug = $this->moduleSlug();

        $validator = Validator::make($request->all(), $this->validation($module));
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $model = $this->modelInstance($module);
        $record = $model->findOrFail($id);
        $data = [];

        foreach ($module['fields'] as $field) {
            $data[$field['name']] = $this->valueForField($request, $module, $field, $record->getAttributes());
        }

        $this->applyApprovalPolicy($data, $record);

        $record->update($data);

        if (method_exists($record, 'createRevision')) {
            $record->createRevision('Diperbarui');
        }

        return redirect()->route('admin.crud.index', $slug)
            ->with('flash_success', 'Perubahan berhasil disimpan.');
    }

    public function destroy(Request $request, $module, $id)
    {
        $this->authorizeModule();

        $module = $this->module();
        $model = $this->modelInstance($module);
        $record = $model->findOrFail($id);

        // Hapus file upload (gambar/dokumen) milik record agar storage bersih.
        foreach ($module['fields'] as $field) {
            if (in_array($field['type'], ['image', 'file'], true)) {
                $path = $record->{$field['name']} ?? null;
                if ($path && File::exists(public_path('uploads/' . $path))) {
                    File::delete(public_path('uploads/' . $path));
                }
            }
        }

        $record->delete();

        return back()->with('flash_success', 'Data berhasil dihapus.');
    }

    public function reorder(Request $request)
    {
        $this->authorizeModule();

        $module = $this->module();
        $model = $this->modelInstance($module);

        if ($request->filled('ids')) {
            $ids = array_values(array_map('intval', (array) $request->input('ids')));

            foreach ($ids as $index => $id) {
                $model->newQuery()->whereKey($id)->update(['urutan' => $index]);
            }
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Toggle kolom boolean `enabled` (aktif/nonaktif) dari tabel index.
     * Hanya modul yang mendeklarasikan `togglable => true` yang di-aktifkan
     * (dari view index; metode ini sendiri netral terhadap konfigurasi).
     */
    public function toggle(Request $request, $module, $id)
    {
        $this->authorizeModule();

        $model = $this->modelInstance($this->module());
        $record = $model->findOrFail($id);

        \abort_if(! \Schema::hasColumn($record->getTable(), 'enabled'), 403, 'Modul ini tidak mendukung toggle aktif/nonaktif.');

        $record->update(['enabled' => ! $record->enabled]);

        return response()->json(['ok' => true, 'enabled' => (bool) $record->enabled]);
    }

    public function singletonIndex()
    {
        $this->authorizeModule();

        $module = $this->module();
        $slug = $this->moduleSlug();
        $model = $this->modelInstance($module);

        $record = $model->first();

        return view('admin.crud.form', compact('module', 'slug', 'record'));
    }

    protected function singletonStore(Request $request, array $module, string $slug)
    {
        $model = $this->modelInstance($module);

        $validator = Validator::make($request->all(), $this->validation($module));
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $record = $model->first() ?? $model;
        $data = [];

        foreach ($module['fields'] as $field) {
            $data[$field['name']] = $this->valueForField($request, $module, $field, $record->getAttributes());
        }

        $record->fill($data)->save();

        if (method_exists($record, 'createRevision')) {
            $record->createRevision('Diperbarui');
        }

        return back()->with('flash_success', 'Perubahan berhasil disimpan.');
    }
}
