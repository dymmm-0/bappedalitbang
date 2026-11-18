<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        abort_if(auth()->user()->cannot('manage_menus'), 403);

        $menus = Menu::withCount('items')->orderBy('id')->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->cannot('manage_menus'), 403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['location'] = $data['location'] ?: $data['slug'];

        Menu::create($data);

        return back()->with('flash_success', 'Menu berhasil dibuat.');
    }

    public function manage(Request $request, $id)
    {
        abort_if(auth()->user()->cannot('manage_menus'), 403);

        $menu = Menu::findOrFail($id);
        $items = MenuItem::where('menu_id', $menu->id)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->with('children')
            ->get();

        return view('admin.menus.manage', compact('menu', 'items'));
    }

    public function storeItem(Request $request, $id)
    {
        abort_if(auth()->user()->cannot('manage_menus'), 403);

        $menu = Menu::findOrFail($id);

        $data = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'parent_id' => 'nullable|integer',
            'target' => 'nullable|string|max:20',
        ]);

        $data['target'] = $data['target'] ?? '_self';
        $data['sort_order'] = MenuItem::where('menu_id', $menu->id)->max('sort_order') + 1;

        $menu->items()->create($data);

        return back()->with('flash_success', 'Item menu ditambahkan.');
    }

    public function updateItem(Request $request, $id, $itemId)
    {
        abort_if(auth()->user()->cannot('manage_menus'), 403);

        $item = MenuItem::findOrFail($itemId);

        $data = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'parent_id' => 'nullable|integer',
            'target' => 'nullable|string|max:20',
            'enabled' => 'nullable|boolean',
        ]);

        $data['enabled'] = $request->boolean('enabled');
        $item->update($data);

        return back()->with('flash_success', 'Item menu diperbarui.');
    }

    public function deleteItem(Request $request, $id, $itemId)
    {
        abort_if(auth()->user()->cannot('manage_menus'), 403);

        MenuItem::findOrFail($itemId)->delete();

        return back()->with('flash_success', 'Item menu dihapus.');
    }

    public function reorder(Request $request, $id)
    {
        abort_if(auth()->user()->cannot('manage_menus'), 403);

        if ($request->filled('ids')) {
            $ids = array_values(array_map('intval', (array) $request->input('ids')));
            foreach ($ids as $index => $itemId) {
                MenuItem::whereKey($itemId)->update(['sort_order' => $index]);
            }
        }

        return response()->json(['ok' => true]);
    }
}
