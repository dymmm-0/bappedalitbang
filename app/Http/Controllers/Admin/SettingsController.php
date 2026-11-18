<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        abort_if(auth()->user()->cannot('manage_settings'), 403);

        $definitions = config('cms.settings');
        $groups = [];

        foreach ($definitions as $groupKey => $definition) {
            $groups[$groupKey] = [
                'label' => $definition['label'],
                'fields' => [],
            ];

            foreach ($definition['fields'] as $field) {
                Setting::firstOrCreate(
                    ['key' => $field['key']],
                    ['value' => $field['default'] ?? '', 'group' => $groupKey, 'type' => $field['type']]
                );

                $groups[$groupKey]['fields'][] = $field;
            }
        }

        $values = all_settings();

        return view('admin.settings.index', compact('groups', 'values'));
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->cannot('manage_settings'), 403);

        $keys = array_keys($request->except(['_token']));

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => (string) $request->input($key, ''), 'group' => (string) $request->input("_{$key}_group", 'umum')]
            );
        }

        settings_forget();

        return back()->with('flash_success', 'Pengaturan berhasil disimpan.');
    }

    public function alamat()
    {
        abort_if(auth()->user()->cannot('manage_settings'), 403);

        $values = all_settings();

        return view('admin.settings.alamat', compact('values'));
    }

    /**
     * Seed a minimal set of default settings (used on first run).
     */
    public static function seedDefaults(): void
    {
        foreach (config('cms.settings') as $groupKey => $definition) {
            foreach ($definition['fields'] as $field) {
                Setting::firstOrCreate(
                    ['key' => $field['key']],
                    ['value' => $field['default'] ?? '', 'group' => $groupKey, 'type' => $field['type']]
                );
            }
        }
    }
}
