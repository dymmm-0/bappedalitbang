<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GaleriVideo;

class Artikel_controller extends Controller
{
    function index(){
        abort_if(auth()->user()->cannot('manage_artikel'), 403);

    	$title = 'List Artikel';
    	$query = \DB::table('artikel as a')->join('users as u','u.id','=','a.user_id')->join('kategori as k', 'k.id', '=','a.id_kategori')->select('a.judul','a.created_at','u.name','k.nama as kategori','a.artikel_id');

    	$this->scopeByBidang($query, 'a.bidang_id');

    	$data = $query->orderBy('a.artikel_id','desc')->get();

    	return view('admin.artikel.artikel_index', compact('title','data'));
    }

    public function add(){
        abort_if(auth()->user()->cannot('manage_artikel'), 403);

    	$title = 'Tambah Artikel';
    	$kategori = \DB::table('kategori')->orderBy('nama','asc')->get();
    	$videoUrl = '';

    	return view('admin.artikel.artikel_add', compact('title','kategori','videoUrl'));
    }

    /**
     * Resolver Video Terkait: admin mengetik URL langsung.
     * URL disimpan di tabel galeri_video (sumber data sama dengan menu Galeri Video).
     * URL sudah ada -> pakai record yang sama (tidak duplikat).
     * URL belum ada -> buat record galeri_video baru lalu kembalikan id-nya.
     * Kosong -> null (lepas hubungan, tidak hapus record video).
     */
    private function resolveVideoId($url, $judul)
    {
    	$url = trim((string) $url);
    	if ($url === '') {
    		return null;
    	}

    	$norm = strtolower($url);
    	$existing = \DB::table('galeri_video')
    		->whereRaw('LOWER(url) = ?', [$norm])
    		->whereNotNull('url')
    		->first();

    	if ($existing) {
    		return $existing->id;
    	}

    	$video = GaleriVideo::withoutGlobalScopes()->create([
    		'bidang_id' => \Auth::user()->bidang_id,
    		'judul'     => $judul,
    		'url'       => $url,
    		'urutan'    => 0,
    		'enabled'   => true,
    	]);

    	return $video->id;
    }

    public function store(Request $request){
        abort_if(auth()->user()->cannot('manage_artikel'), 403);

    	$this->validate($request,[
    		'judul'=>'required',
    		'isi'=>'required',
    		'id_kategori'=>'required'

    	]);

    	$file =$request->file('image');

    	$data = array();
    	$data['id_kategori'] = $request->id_kategori;
    	$data['judul'] = $request->judul;
    	$data['isi'] = $request->isi;
    	$data['user_id'] = \Auth::user()->id;
    	$data['bidang_id'] = \Auth::user()->bidang_id;
    	$data['video_id'] = $this->resolveVideoId($request->video_url, $request->judul);
    	$data['created_at'] = date('Y-m-d H:i:s');
    	$data['updated_at'] = date('Y-m-d H:i:s');

        // Workflow persetujuan: admin bidang -> pending (belum tampil publik),
        // Kepala Bappedalitbang / Super Admin -> langsung terbit.
        $isApprover = \Auth::user()->isSuperadmin();
        $data['status'] = $isApprover ? 'published' : 'pending';
        if (! $isApprover) {
            $data['submitted_by'] = \Auth::user()->id;
            $data['submitted_at'] = date('Y-m-d H:i:s');
        }

    	if($file){
    		$data['gambar'] = store_upload($file, 'artikel/' . date('Y'));
    	}

    	\DB::table('artikel')->insert($data);
    	\Session::flash('sukses', 'Data berhasil diinput');
    	return redirect('admin/artikel');
    }

    public function edit($id){
        abort_if(auth()->user()->cannot('manage_artikel'), 403);

    	$title = 'Edit Artikel';
    	$dt = \DB::table('artikel')->where('artikel_id',$id)->first();

    	$this->ensureAccessible($dt);

    	$kategori = \DB::table('kategori')->orderBy('nama','asc')->get();
    	$videoUrl = '';
    	if (! empty($dt->video_id)) {
    		$vid = \DB::table('galeri_video')->where('id', $dt->video_id)->first();
    		$videoUrl = $vid ? $vid->url : '';
    	}

    	return view('admin.artikel.artikel_edit', compact('dt','title','kategori','videoUrl'));
    }

    public function update(Request $request, $id){
        abort_if(auth()->user()->cannot('manage_artikel'), 403);

    	$this->validate($request,[
    		'judul'=>'required',
    		'isi'=>'required',
    		'id_kategori'=>'required'
    	]);

    	$query = \DB::table('artikel')->where('artikel_id', $id);
    	$this->scopeByBidang($query, 'bidang_id');

    	$file =$request->file('image');

    	$data = array();
    	$data['id_kategori'] = $request->id_kategori;
    	$data['judul'] = $request->judul;
    	$data['isi'] = $request->isi;
    	$data['user_id'] = \Auth::user()->id;
    	$data['video_id'] = $this->resolveVideoId($request->video_url, $request->judul);
    	$data['updated_at'] = date('Y-m-d H:i:s');

        // Admin bidang yang mengedit konten harus mengajukan ulang
        // (status kembali pending); hanya Kepala Bappedalitbang yang menerbitkan.
        $isApprover = \Auth::user()->isSuperadmin();
        $data['status'] = $isApprover ? 'published' : 'pending';
        if (! $isApprover) {
            $data['submitted_by'] = \Auth::user()->id;
            $data['submitted_at'] = date('Y-m-d H:i:s');
        }

    	if($file){
    		$data['gambar'] = store_upload($file, 'artikel/' . date('Y'));
    	}

    	$query->update($data);

    	\Session::flash('sukses', 'Data berhasil diupdate');

    	return redirect('admin/artikel');
    }

    public function delete($id){
        abort_if(auth()->user()->cannot('manage_artikel'), 403);

    	$query = \DB::table('artikel')->where('artikel_id',$id);
    	$this->scopeByBidang($query, 'bidang_id');

    	$query->delete();

    	\Session::flash('sukses', 'Data berhasil dihapus');
    	return redirect('admin/artikel');

    }

    /**
     * Filter query hanya untuk data bidang admin yang sedang login.
     */
    private function scopeByBidang($query, string $column)
    {
        $user = auth()->user();

        if ($user->bidang_id && ! $user->isSuperadmin()) {
            $query->where($column, $user->bidang_id);
        }
    }

    /**
     * Admin bidang tidak boleh mengakses artikel milik bidang lain.
     */
    private function ensureAccessible($record)
    {
        $user = auth()->user();

        if (! $record || ($user->bidang_id && ! $user->isSuperadmin() && (int) $record->bidang_id !== (int) $user->bidang_id)) {
            abort(404);
        }
    }
}
