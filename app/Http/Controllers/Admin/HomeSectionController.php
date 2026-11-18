<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use Illuminate\Http\Request;

class HomeSectionController extends Controller
{
    public function index()
    {
        abort_if(auth()->user()->cannot('manage_home_sections'), 403);

        $registry = config('cms.home_sections');
        $sections = HomeSection::orderBy('sort_order')->get()->keyBy('type');

        foreach ($registry as $type => $definition) {
            if (! HomeSection::withTrashed()->where('type', $type)->exists()) {
                $row = HomeSection::create([
                    'type' => $type,
                    'title' => $definition['label'],
                    'enabled' => true,
                    'sort_order' => array_search($type, array_keys($registry)),
                ]);
                $sections->put($type, $row);
            }
        }

        $sections = HomeSection::orderBy('sort_order')->get()->keyBy('type');

        return view('admin.home_sections.index', compact('sections', 'registry'));
    }

    public function update(Request $request, $id)
    {
        abort_if(auth()->user()->cannot('manage_home_sections'), 403);

        $section = HomeSection::findOrFail($id);

        $section->update([
            'title' => $request->input('title', $section->title),
            'subtitle' => $request->input('subtitle'),
            'badge' => $request->input('badge'),
            'enabled' => $request->boolean('enabled'),
        ]);

        return back()->with('flash_success', 'Pengaturan section disimpan.');
    }

    public function destroy($id)
    {
        abort_if(auth()->user()->cannot('manage_home_sections'), 403);

        HomeSection::findOrFail($id)->delete();

        return back()->with('flash_success', 'Section dihapus.');
    }

    public function reorder(Request $request)
    {
        abort_if(auth()->user()->cannot('manage_home_sections'), 403);

        if ($request->filled('types')) {
            $types = array_values((array) $request->input('types'));
            foreach ($types as $index => $type) {
                HomeSection::where('type', $type)->update(['sort_order' => $index]);
            }
        }

        return response()->json(['ok' => true]);
    }
}
