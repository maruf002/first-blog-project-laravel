<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    public function index(){
      $posts = Auth::user()->favourite_Posts;
      return view('author.favourite',compact('posts'));
    }
}
