<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class AuthorController extends Controller
{
    public function index(){
        $authors = User::authors() //authors() = scope defined in user model
               ->withCount('posts')  
               ->withcount('comments') 
               ->withcount('favourite_posts') 
               ->get();
               
       return view('admin.authors',compact('authors'));
       
    }

    public function destroy($id){
      return $author = User::findorFail($id)->delete();
       Toastr::success('Author Successfully Deleted','Success');
        return redirect()->back();
    }
}
