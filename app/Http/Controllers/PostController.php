<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Post;
use App\Tag;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{

     public function index(){
        $posts = Post::latest()->approved()->published()->paginate(6); //approved() = scope in post model
         return view('posts',compact('posts'));
     }


    public function detailes($slug){
        $post = Post::where('slug',$slug)->approved()->published()->first();
        //get() also can be used but get has chance to produce multiple slug so.....
        $blogkey = 'blog_' . $post->id;
      
        if(!session::has($blogkey)){
            $post->increment('view_count');
            session::put($blogkey,1);
        }
        $randomposts = Post::approved()->published()->take(3)->inRandomOrder()->get();
        return view('post',compact('post','randomposts'));
    }

    public function postByCategory($slug){
     
           $category = Category::where('slug',$slug)->first();
           $posts = $category->posts()->approved()->published()->get();
          
           return view('category_posts',compact('category','posts'));


    }

    public function postByTag($slug){
      $tags= Tag::where('slug',$slug)->first();
      $posts = $tags->posts()->approved()->published()->get();
      return view('tag_posts',compact('tags','posts'));
    }
}
