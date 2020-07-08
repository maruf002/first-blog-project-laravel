@extends('layouts.frontend.app')

@section('title')
	{{$post->title}}
@endsection


@push('css')
    
	<link href="{{asset('assets/frontend/css/single-post/styles.css')}}" rel="stylesheet">

	<link href="{{asset('assets/frontend/css/single-post/responsive.css')}}" rel="stylesheet">
 

	 <style>
        .header-bg{
            height: 400px;
            width: 100%;
            background-image: url({{ Storage::disk('public')->url('post/'.$post->image) }});
            background-size: cover;
        }
        .favorite_posts{
            color: blue;
        }
		</style>

@endpush

@section('content')
    
	<div class="slider">
		<div class="display-table  center-text">
			<h1 class="title display-table-cell"><b>DESIGN</b></h1>
		</div>
	</div><!-- slider -->

	<section class="post-area section">
		<div class="container">

			<div class="row">

				<div class="col-lg-8 col-md-12 no-right-padding">

					<div class="main-post">

						<div class="blog-post-inner">

							<div class="post-info">

								<div class="left-area">
									<a class="avatar" href="{{route('author.profile',$post->user->username)}}"><img src="{{Storage::disk('public')->url('profile/'.$post->user->image)}}" alt="profile image"></a>
								</div>

								<div class="middle-area">
									<a class="name" href="#"><b>{{$post->user->name}}</b></a>
									<h6 class="date">{{$post->created_at->diffForHumans()}}</h6>
								</div>

							</div><!-- post-info -->

						<h3 class="title"><a href="#"><b>{{$post->title}}</b></a></h3>

						 

							<div class="para">
								{!! html_entity_decode($post->body) !!}
							</div>

							<ul class="tags">
								@foreach($post->tags as $key => $tag)
							<li><a href="{{route('tag.posts',$tag->slug)}}">{{$tag->name}}</a></li>
								@endforeach
							
							</ul>
						</div><!-- blog-post-inner -->

						<div class="post-icons-area">
							<ul class="post-icons">
								
									<li>
										@guest
										<a href="javascript:void(0);" onclick="toastr.info('To add favourite list, you need to login first.','Info',{
											closeButton: true,
											progressbar: true,
										})"><i class="ion-heart"></i>{{$post->favourite_to_users->count()}}</a>
											
										@else
		 
									 <a href="javascript:void(0);" onclick="document.getElementById('favourite-form-{{$post->id}}').submit();" class="{{!Auth::user()->favourite_posts->where('pivot.post_id',$post->id)->count() == 0 ? 'favourite_posts' : '' }}">
										 <i class="ion-heart"></i>{{$post->favourite_to_users->count()}}</a>
		 
								 <form id="favourite-form-{{$post->id}}" action="{{route('post.favourite',$post->id)}}" method="POST" style="display: none;">
										 @csrf
		 
									 </form>  
										   
										@endguest
								</li>
								<li><a href="#"><i class="ion-chatbubble"></i>6</a></li>
								<li><a href="#"><i class="ion-eye"></i>138</a></li>
							</ul>

							<ul class="icons">
								<li>SHARE : </li>
								<li><a href="#"><i class="ion-social-facebook"></i></a></li>
								<li><a href="#"><i class="ion-social-twitter"></i></a></li>
								<li><a href="#"><i class="ion-social-pinterest"></i></a></li>
							</ul>
						</div>

					


					</div><!-- main-post -->
				</div><!-- col-lg-8 col-md-12 -->

				<div class="col-lg-4 col-md-12 no-left-padding">

					<div class="single-post info-area">

						<div class="sidebar-area about-area">
							<h4 class="title"><b>ABOUT AUTHOR</b></h4>
							<p>{{$post->user->about}}</p>
						</div>


						<div class="tag-area">

							<h4 class="title"><b>Cateogies</b></h4>
							<ul>
								@foreach($post->categories as $key => $cat)
							<li><a href="{{route('category.posts',$cat->slug)}}">{{$cat->name}}</a></li>
								@endforeach
							
							</ul>

						</div><!-- subscribe-area -->

					</div><!-- info-area -->

				</div><!-- col-lg-4 col-md-12 -->

			</div><!-- row -->

		</div><!-- container -->
	</section><!-- post-area -->


	<section class="recomended-area section">
		<div class="container">
			<div class="row">
             @foreach($randomposts as $key => $rand)
				 
			
				<div class="col-lg-4 col-md-6">
					<div class="card h-100">
						<div class="single-post post-style-1">

							<div class="blog-image"><img src="{{ Storage::disk('public')->url('post/'.$rand->image) }}" alt="{{$rand->title}}"></div>

				<a class="avatar" href="{{route('author.profile',$rand->user->username)}}"><img src="{{ Storage::disk('public')->url('profile/'.$rand->user->image) }}" alt="Profile Image"></a>

							<div class="blog-info">

							<h4 class="title"><a href="{{route('post.detailes',$rand->slug)}}"><b>{{$rand->title}}</b></a></h4>

								<ul class="post-footer">
								
										<li>
											@guest
											<a href="javascript:void(0);" onclick="toastr.info('To add favourite list, you need to login first.','Info',{
												closeButton: true,
												progressbar: true,
											})"><i class="ion-heart"></i>{{$rand->favourite_to_users->count()}}</a>
												
											@else
			 
										 <a href="javascript:void(0);" onclick="document.getElementById('favourite-form-{{$rand->id}}').submit();" class="{{!Auth::user()->favourite_posts->where('pivot.post_id',$rand->id)->count() == 0 ? 'favourite_posts' : '' }}">
											 <i class="ion-heart"></i>{{$post->favourite_to_users->count()}}</a>
			 
									 <form id="favourite-form-{{$rand->id}}" action="{{route('post.favourite',$rand->id)}}" method="POST" style="display: none;">
											 @csrf
			 
										 </form>  
											   
											@endguest
									</li>
									<li><a href="#"><i class="ion-chatbubble"></i>{{$rand->comments->count()}}</a></li>
									<li><a href="#"><i class="ion-eye"></i>{{$post->view_count}}</a></li>
								</ul>

							</div><!-- blog-info -->
						</div><!-- single-post -->
					</div><!-- card -->
				</div><!-- col-md-6 col-sm-12 -->

				@endforeach

		

			</div><!-- row -->

		</div><!-- container -->
	</section>

	<section class="comment-section">
		<div class="container">
			<h4><b>POST COMMENT</b></h4>
			<div class="row">

				<div class="col-lg-8 col-md-12">
					<div class="comment-form">
						@guest
						<p>For post a new comment, you need to login first.</p>
					<a href="{{route('login')}}">Login</a>
						@else
					<form method="post" action="{{route('comment.store',$post->id)}}">
						@csrf
								<div class="row">

									<div class="col-sm-12">
										<textarea name="comment" rows="2" class="text-area-messge form-control"
											placeholder="Enter your comment" aria-required="true" aria-invalid="false"></textarea >
									</div><!-- col-sm-12 -->
									<div class="col-sm-12">
										<button class="submit-btn" type="submit" id="form-submit" ><b>POST COMMENT</b></button>
									</div><!-- col-sm-12 -->

								</div><!-- row -->
							</form>

						@endguest

					</div><!-- comment-form -->

					<h4><b>Comments({{$post->comments->count()}})</b></h4>
					@if($post->comments->count() == 0)
						<p>No Comment yet. Be the first :)</p>
					@else
					
					@foreach($post->comments as $key => $comment)						
					<div class="commnets-area ">

						<div class="comment">

							<div class="post-info">

								<div class="left-area">
								<a class="avatar" href="#"><img src="{{Storage::disk('public')->url('profile/'.$comment->user->image)}}"></a>
								</div>

								<div class="middle-area">
									<a class="name" href="#"><b>{{$comment->user->name}}</b></a>
				                     <h6 class="date">{{$comment->created_at->diffForHumans()}}</h6>
								</div>

								<div class="right-area">
									<h5 class="reply-btn" ><a href="#"><b>REPLY</b></a></h5>
								</div>

							</div><!-- post-info -->

						<p>{{$comment->comment}}</p>

						</div>

					</div><!-- commnets-area -->
                   @endforeach
				
						@endif
				</div><!-- col-lg-8 col-md-12 -->

			</div><!-- row -->

		</div><!-- container -->
	</section>
@endsection

@push('js')

@endpush