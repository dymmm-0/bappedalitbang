	<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<div class="section-title">
							<h2>Recent Posts</h2>
						</div>
					</div>

					<!-- post -->
					@php
					$artikel = \DB::table('artikel as a')->join('kategori as k','k.id','=','a.id_kategori')->select('a.judul','a.isi','a.gambar','a.created_at','k.nama as kategori')->orderby('created_at','desc')->get();
					@endphp
					@foreach($artikel as $at)
					<div class="col-md-4">
						<div class="post">
							<a class="post-img" href="blog-post.html"><img src="{{ asset('uploads/'.$at->gambar) }}" alt=""></a>
								<div class="post-meta">
									<a class="post-category cat-1" href="category.html">{{ $at->kategori }}</a>
									<span class="post-date">{{ date('d M Y',strtotime($at->created_at)) }}</span>
								</div>
								<h3 class="post-title"><a href="blog-post.html">{{ $at->judul}}</a></h3>
								<p>{!! substr($at->isi,0,400) !!} ....</p>		
						</div>
					</div>
					@endforeach
					<!-- /post -->

					
				</div>

				<!-- /row -->