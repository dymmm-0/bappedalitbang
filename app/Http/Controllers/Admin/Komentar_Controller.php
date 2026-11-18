<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Komentar_Controller extends Controller
{
    public function index(){
        abort_if(auth()->user()->cannot('manage_komentar'), 403);

    	$title = 'Manage Komentar';

    	$query = \DB::table('komentar as k')->join('artikel as a', 'a.artikel_id','=','k.artikel_id')->select('a.judul', 'k.nama','k.email', 'k.isi', 'k.created_at', 'k.komentar_id');

        $user = auth()->user();
        if ($user->bidang_id && ! $user->isSuperadmin()) {
            $query->where('a.bidang_id', $user->bidang_id);
        }

    	$data = $query->orderBy('k.komentar_id','desc')->get();

    	return view ('admin.komentar.komentar_index', compact ('title', 'data'));

    }
     public function delete($komentar_id){
        abort_if(auth()->user()->cannot('manage_komentar'), 403);

        $query = \DB::table('komentar')->where('komentar_id',$komentar_id);

        $user = auth()->user();
        if ($user->bidang_id && ! $user->isSuperadmin()) {
            $query->whereIn('artikel_id', function ($q) use ($user) {
                $q->select('artikel_id')->from('artikel')->where('bidang_id', $user->bidang_id);
            });
        }

     	$query->delete();

     	return redirect('admin/komentar');
     }
}
