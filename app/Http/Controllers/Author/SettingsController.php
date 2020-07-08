<?php

namespace App\Http\Controllers\Author;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index(){
        return view('author.settings');
    }
    public function updateprofile(Request $request){
        $this->validate($request,[
           'name' => 'required',
           'email'=> 'required|email',
           'image'=> 'required|image',

        ]);

        $image = $request->file('image');
        $slug  = str_slug('$request->name');
        $user  = User::findorFail(Auth::id());
        if(isset($image)){
            $currentDate= Carbon::now()->toDateString();
            $imageName  = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if(!storage::disk('public')->exists('profile')){
                stograge::makeDirectory('profile');
            }
            //delete old image from profile folder
            if(storage::disk('public')->exists('profile/'.$user->image)){
                storage::disk('public')->delete('profile/'.$user->image);
            }

            $profile = Image::make($image)->resize(500,500)->stream();
            Storage::disk('public')->put('profile/'.$imageName,$profile);
        }else{
            $imageName = $user->image;
        }

        $user->name = $request->name;
        $user->email= $request->email;
        $user->image= $imageName;
        $user->about= $request->about;
        $user->save();
        Toastr::success('Profile Successfully Updated','Success');
        return redirect()->back();

    }

    public function updatepassword(Request $request){
        $this->validate($request,[
           'old_password' => 'required',
           'password'     => 'required|confirmed',
        ]);

      
     
         $hashedpassword = Auth::user()->password;
        if(Hash::check($request->old_password, $hashedpassword)){
             if(!Hash::check($request->password, $hashedpassword)){
                 $user = User::find(Auth::id());
                $user->password = Hash::make($request->password);
                 $user->save();
                 Toastr::success('Password Successfully Changed','Success');
                 Auth::logout();
                return redirect()->back();

            }else{
                Toastr::error('New password can not be the same as old password.','Error');
                return redirect()->back();
            }
        }else{
        Toastr::error('Current password not match.','Error');
            return redirect()->back();
         }
    }
}
