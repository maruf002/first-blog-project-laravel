@extends('layouts.frontend.app')

@section('title', 'PROFILE')


 @push('css')
   
	<link href="{{asset('assets/frontend/css/profile/styles.css')}}" rel="stylesheet">

	<link href="{{asset('assets/frontend/css/profile/responsive.css')}}" rel="stylesheet">
 

	 <style>
    
        .favourite_posts{
            color: blue;
        }
		</style>

@endpush

@section('content')

<div class="slider display-table center-text">
    <h1 class="title display-table-cell"><b>{{$author->name}}</b></h1>
</div><!-- slider -->

<section class="blog-area section">
    <div class="container">

        <div class="row">

            <div class="col-lg-8 col-md-12">
                <div class="row">
          @if($posts->count()>0)
              
                 
		@foreach($posts as $key => $post)
		<div class="col-lg-6 col-md-6">
			<div class="card h-100">
				<div class="single-post post-style-1">

					<div class="blog-image"><img src="{{Storage::disk('public')->url('post/'.$post->image)}}" alt="Blog Image"></div>

					<a class="avatar" href="{{route('author.profile',$post->user->username)}}"><img src="{{Storage::disk('public')->url('profile/'.$post->user->image)}}"></a>

					<div class="blog-info">

					<h4 class="title"><a href="{{route('post.detailes',$post->slug)}}"><b>{{$post->title}}</b></a></h4>

						<ul class="post-footer">
							
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
							<li><a href="#"><i class="ion-chatbubble"></i>{{$post->comments->count()}}</a></li>
							<li><a href="#"><i class="ion-eye"></i>{{$post->view_count}}</a></li>
						</ul>

					</div><!-- blog-info -->
				</div><!-- single-post -->
			</div><!-- card -->
        </div>
        
        @endforeach
        @else
          
        <div class="single-post info-area ">

            <div class="about-area">
                <h4 class="title"><b>Sorry, This User Has Not Posted Anything Yet.</b></h4>
            
            </div>

   

        </div><!-- info-area -->
        @endif
                </div><!-- row -->

                <a class="load-more-btn" href="#"><b>LOAD MORE</b></a>

            </div><!-- col-lg-8 col-md-12 -->

            <div class="col-lg-4 col-md-12 ">

                <div class="single-post info-area ">

                    <div class="about-area">
                        <h4 class="title"><b>ABOUT AUTHOR</b></h4>
                    <P>{{$author->name}}</P> <br>
                    <p>{{$author->about}}</p>
                    <strong>Author Since : {{$author->created_at->toDateString() }}</strong> <br>
                    <strong>Total Posts : {{$author->posts->count()}}</strong>
                    </div>

           

                </div><!-- info-area -->

            </div><!-- col-lg-4 col-md-12 -->

        </div><!-- row -->

    </div><!-- container -->
</section><!-- section -->

  
@endsection

@push('js')

@endpush