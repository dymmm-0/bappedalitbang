<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
class Beranda_controller extends Controller
{
    public function index(){
        $heroBerita = \DB::table('artikel as a')->join('kategori as k','k.id','=','a.id_kategori')->where('a.status','published')->whereNull('a.deleted_at')->select('a.artikel_id','a.judul','a.gambar','a.isi','a.created_at','k.nama as kategori')->orderBy('a.created_at','desc')->limit(5)->get();

        return view('welcome',compact('heroBerita'));
    }
 
    public function search(Request $request){
        $q = trim((string) $request->input('search'));

        if ($q === '') {
            return view('search', ['q' => $q, 'results' => collect(), 'total' => 0, 'groupCounts' => collect()]);
        }

        $term = '%' . $q . '%';

        $artikel = \DB::table('artikel as a')->join('kategori as k', 'k.id', '=', 'a.id_kategori')
            ->where('a.status', 'published')
            ->where(function ($b) use ($term) {
                $b->where('a.judul', 'like', $term)->orWhere('a.isi', 'like', $term);
            })
            ->select('a.artikel_id', 'a.judul', 'a.gambar', 'a.isi', 'a.created_at', 'k.nama as kategori')
            ->get()
            ->map(fn ($r) => [
                'tipe' => 'artikel',
                'judul' => $r->judul,
                'url' => url('detail/' . $r->artikel_id),
                'gambar' => $r->gambar,
                'meta' => $r->kategori,
                'konten' => strip_tags($r->isi),
                'tanggal' => $r->created_at,
            ]);

        $pengumuman = \DB::table('pengumuman')
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->where(function ($b) use ($term) {
                $b->where('judul', 'like', $term)->orWhere('isi', 'like', $term);
            })
            ->select('id', 'judul', 'isi', 'tanggal')
            ->get()
            ->map(fn ($r) => [
                'tipe' => 'pengumuman',
                'judul' => $r->judul,
                'url' => url('pengumuman/' . $r->id),
                'gambar' => null,
                'meta' => 'Pengumuman',
                'konten' => strip_tags($r->isi),
                'tanggal' => $r->tanggal,
            ]);

        $penelitian = \DB::table('penelitian')
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->where(function ($b) use ($term) {
                $b->where('judul', 'like', $term)->orWhere('ringkasan', 'like', $term)->orWhere('jenis', 'like', $term);
            })
            ->select('id', 'judul', 'jenis', 'tahun', 'ringkasan', 'gambar')
            ->get()
            ->map(fn ($r) => [
                'tipe' => 'penelitian',
                'judul' => $r->judul,
                'url' => url('penelitian/' . $r->id),
                'gambar' => $r->gambar,
                'meta' => trim(implode(' · ', array_filter([$r->jenis, $r->tahun]))),
                'konten' => strip_tags((string) $r->ringkasan),
                'tanggal' => null,
            ]);

        $dokumen = \DB::table('dokumen')
            ->where('enabled', true)
            ->where(function ($b) use ($term) {
                $b->where('judul', 'like', $term)->orWhere('kategori', 'like', $term);
            })
            ->select('id', 'judul', 'kategori', 'file')
            ->get()
            ->map(fn ($r) => [
                'tipe' => 'dokumen',
                'judul' => $r->judul,
                'url' => media_url($r->file),
                'gambar' => null,
                'meta' => trim($r->kategori ?? ''),
                'konten' => '',
                'tanggal' => null,
            ]);

        $groupCounts = collect([
            'artikel' => $artikel->count(),
            'pengumuman' => $pengumuman->count(),
            'penelitian' => $penelitian->count(),
            'dokumen' => $dokumen->count(),
        ]);

        $merged = $artikel->concat($pengumuman)->concat($penelitian)->concat($dokumen)
            ->sortByDesc('tanggal')
            ->values();

        $total = $merged->count();
        $perPage = 12;
        $page = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $items = $merged->forPage($page, $perPage)->values();
        $results = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('search', compact('q', 'results', 'total', 'groupCounts'));
    }
 
    public function detail($artikel_id){
        $artikel = \DB::table('artikel as a')
            ->leftJoin('users as u','a.user_id','=','u.id')
            ->where('a.artikel_id',$artikel_id)
            ->where('a.status','published')
            ->whereNull('a.deleted_at')
            ->select('a.*','u.name as nama')
            ->first();
        if (!$artikel) {
            abort(404);
        }
        $komentar = \DB::table('komentar')->where('artikel_id',$artikel_id)->get();
        $total_komentar = \DB::table('komentar')->where('artikel_id',$artikel_id)->count();

        $video = null;
        if (! empty($artikel->video_id)) {
            $video = \DB::table('galeri_video')->where('id', $artikel->video_id)->where('enabled', true)->first();
        }

        return view('detail',compact('artikel','komentar','total_komentar','video'));
    }
 
    public function komentar(Request $request, $artikel_id){
        \DB::table('komentar')->insert([
            'artikel_id'=>$artikel_id,
            'nama'=>$request->nama,
            'email'=>$request->email,
            'website'=>$request->website ?? '',
            'isi'=>$request->isi,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
 
        return redirect('detail/'.$artikel_id);
    }
 
    public function artikel(){
        $data = \DB::table('artikel as a')->join('kategori as k','k.id','=','a.id_kategori')->where('a.status','published')->select('a.judul','a.isi','a.gambar','a.created_at','k.nama as kategori','a.artikel_id')->orderby('created_at','desc')->paginate(9);
        $kategoriName = 'Berita';

        return view('kategori',compact('data','kategoriName'));
    }

    public function artikel_kategori($kategori_id){
        $data = \DB::table('artikel as a')->join('kategori as k','k.id','=','a.id_kategori')->where('a.status','published')->where('a.id_kategori',$kategori_id)->select('a.judul','a.isi','a.gambar','a.created_at','k.nama as kategori','a.artikel_id')->orderby('created_at','desc')->get();

        return view('kategori',compact('data'));
    }

    public function kirimKontak(Request $request){
        $request->validate([
            'nama'   => 'required|string|max:150',
            'email'  => 'required|email|max:150',
            'subjek' => 'required|string|max:200',
            'pesan'  => 'required|string|max:2000',
        ]);

        \DB::table('kontak')->insert([
            'nama'   => $request->nama,
            'email'  => $request->email,
            'subjek' => $request->subjek,
            'pesan'  => $request->pesan,
            'dibaca' => false,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect('/#kontak')->with('success', 'Pesan Anda telah terkirim. Terima kasih atas saran dan aspirasi Anda.');
    }
}