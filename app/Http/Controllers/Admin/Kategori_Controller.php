<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Kategori_Controller extends Controller
{
    public function index(){
        abort_if(auth()->user()->cannot('manage_kategori'), 403);

    	$title = 'Manage Kategori';
    	$kategori = \DB::table('kategori as k')->join('users as u','k.user_id', '=', 'u.id')->select('k.nama', 'u.name', 'k.created_at', 'k.id')->get();

    	return view('admin.kategori.kategori_index', compact('title','kategori'));
    }

    public function add(){
        abort_if(auth()->user()->cannot('manage_kategori'), 403);

    	$title = 'Tambah Kategori';
    	// $users = \DB::table('users')->get();
    	
    	return view('admin.kategori.kategori_add', compact('title'));

    }

    public function store(Request $request){
        abort_if(auth()->user()->cannot('manage_kategori'), 403);

    	$this->validate($request,[
    		'nama'=>'required'

    	]);

    	$nama = $request->nama;

    	\DB::table('kategori')->insert([
    		'nama'=>$nama,
    		'user_id'=>\Auth::user()->id,
    		'created_at'=>date('Y-m-d H:i:s'),
    		'updated_at'=>date('Y-m-d H:i:s'),
    	]);

    	return redirect ('admin/kategori');
    }

    public function edit($id){
        abort_if(auth()->user()->cannot('manage_kategori'), 403);

    	$title = 'Edit kategori';
    	$kategori = \DB::table('kategori')->where('id',$id)->first();
    	return view('admin.kategori.kategori_edit', compact('kategori', 'title'));
    }

    public function update(Request $request, $id){
        abort_if(auth()->user()->cannot('manage_kategori'), 403);

    	\DB::table('kategori')->where('id',$id)->update([
    		'nama'=>$request->nama,
    		'updated_at'=>date('Y-m-d H:i:s')
    	]);
    	return redirect ('admin/kategori');
    	
    }

    public function delete($id){
        abort_if(auth()->user()->cannot('manage_kategori'), 403);

    	\DB::table('kategori')->where('id',$id)->delete();

    	return redirect ('admin/kategori');
    }
}
