<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Subscriber;
use Brian2694\Toastr\Facades\Toastr;

class SubscriberController extends Controller
{
    public function index(){
      $subscribers = Subscriber::latest()->get();
      return view('admin.subscriber',compact('subscribers'));

    }

    //$subscriber = any variable you want 

    public function destroy($subscriber){
        $subscriber = Subscriber::findorFail($subscriber);
         $subscriber->delete();
         Toastr::success('Subscriber successfully deleted','Success');
         return redirect()->back();

    }
}
