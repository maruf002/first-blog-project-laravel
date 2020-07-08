<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewAuthorPost;
use Illuminate\Support\Facades\Notification;
use App\Post;
use App\User;
use App\Category;
use App\Tag;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Auth::user()->posts()->get();//posts() is a function in user model & auth:: for checking authentication of user
        return view('author.post.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags       = Tag::all();
        return view('author.post.create',compact('categories','tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          
        $this->validate($request,[
            'title' =>'required',
            'image' =>'required',
            'categories'=>'required', 
            'tags'=>'required', 
            'body'=>'required', 
    
            ]);
    
            $image = $request->file('image');
            $slug  = str_slug($request->title);
            if(isset($image)){
                //make unique name for image
             $currentDate = carbon::now()->toDatestring();
                $imageName   = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
                if(!storage::disk('public')->exists('post')){
                    storage::disk('public')->makeDirectory('post');
             }
               $postImage = Image::make($image)->resize(1600,1066)->stream();
               storage::disk('public')->put('post/'.$imageName,$postImage);
           }else{
               $image = "default.png";
           }
           $post = new Post();
           $post->user_id = Auth::id(); //auth::id()= present authinticated id
           $post->title   = $request->title;
           $post->slug    = $slug;
           $post->image   = $imageName;
           $post->body    = $request->body;
           if(isset($request->status)){
               $post->status = true;
           }else{
               $post->status = false;
           }
    
           $post->is_approved = false; //for admin we forcefully make it true , but in author there will something else
          
           $post->save();
         //$request->tags=tags array by select name
         
         $post->categories()->attach($request->categories); //categories()=function in post model 
         $post->tags()->attach($request->tags);

         $users = User::where('role_id','1')->get();
         Notification::send($users, new NewAuthorPost($post));
         Toastr::success('Post Successfully Saved :)','Success');
         return redirect()->route('author.post.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if($post->user_id != Auth::id()){
            Toastr::error('you are not authorized to access this post','Error');
            return redirect()->back();

        }
        return view('author.post.show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {

        if($post->user_id != Auth::id()){
            Toastr::error('you are not authorized to access this post','Error');
            return redirect()->back();
        }
       
        $categories = Category::all();
        $tags = Tag::all();
        return view('author.post.edit',compact('post','categories','tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {

        if($post->user_id != Auth::id()){
            Toastr::error('you are not authorized to access this post','Error');
            return redirect()->back();
        }
         
        $this->validate($request,[
            'title' =>'required',
            'image' =>'image', //filetype should be image 
            'categories'=>'required', 
            'tags'=>'required', 
            'body'=>'required', 
   
           ]);
   
           $image = $request->file('image');
           $slug  = str_slug($request->title);
           if(isset($image)){
               //make unique name for image
            $currentDate = carbon::now()->toDatestring();
               $imageName   = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
               if(!storage::disk('public')->exists('post')){
                   storage::disk('public')->makeDirectory('post');
            }
   
            //delete old post image
            if(storage::disk('public')->exists('post/'.$post->image)){
                storage::disk('public')->delete('post/'.$post->image);
            }
   
              $postImage = Image::make($image)->resize(1600,1066)->stream();
              storage::disk('public')->put('post/'.$imageName,$postImage);
          }else{
              $imageName = $post->image;
          }
         
          $post->user_id = Auth::id(); //auth::id()= present authinticated id
          $post->title   = $request->title;
          $post->slug    = $slug;
          $post->image   = $imageName;
          $post->body    = $request->body;
          if(isset($request->status)){
              $post->status = true;
          }else{
              $post->status = false;
          }
   
          $post->is_approved = true; //for admin we forcefully make it true , but in author there will something else
         
          $post->save();
        //$request->tags=tags array by select name
        
        $post->categories()->sync($request->categories); //categories()=function in post model 
        $post->tags()->sync($request->tags);
         
          Toastr::success('Post Successfull saved','success');
           return redirect()->route('author.post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if($post->user_id != Auth::id()){
            Toastr::error('you are not authorized to access this post','Error');
            return redirect()->back();
        }

        if(storage::disk('public')->exists('post/'.$post->image)){
            storage::disk('public')->delete('post/'.$post->image);
        }

        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();
        Toastr::success('Post Successfully deleted','success');
        return redirect()->back();

    }
}
