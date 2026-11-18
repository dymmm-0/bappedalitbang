<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * AJAX upload used by the rich text editor (Summernote).
     */
    public function upload(Request $request)
    {
        abort_if(auth()->user()->cannot('manage_media'), 403);

        $imageMimes = implode(',', (array) config('uploads.allowed_image_extensions', ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']));

        $request->validate(['file' => 'required|image|mimes:' . $imageMimes . '|max:20480']);

        $file = $request->file('file');
        $stored = store_upload($file, 'summernote/' . date('Y'));

        return response()->json(['url' => media_url($stored)]);
    }
}
