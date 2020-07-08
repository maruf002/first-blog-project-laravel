<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FavouriteController extends Controller
{
    public function index(){
      //this means user model->access post model directly beacuse favourite_Posts= App\post, so now it is actually post model
      
        $posts = Auth::user()->favourite_posts;
        return view('admin.favourite',compact('posts'));
    }
}
