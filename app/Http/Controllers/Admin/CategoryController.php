<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $categories = category::latest()->get();
        return view('admin.category.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
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
          'name'  => 'required|unique:categories',  //categories = table name
          'image' => 'required|mimes:jpeg,bmp,png,jpg'

      ]);

      //get form image
      $image = $request->file('image');
      $slug  = str_slug($request->name);
      if(isset($image)){
         //make unique name for image
         $currentDate = carbon::now()->toDateString();  
         $imagename   = $slug. '-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
        //check category dir is exists
        //storage folder->app->public->category
        if(!storage::disk('public')->exists('category')){
            storage::disk('public')->makeDirectory('category');
        }

    //resize image for category & upload

    //must insatll image.intervention.io before using image resize or any other ,,,

         $category = Image::make($image)->resize(1600,479)->stream();
         storage::disk('public')->put('category/'.$imagename,$category);

         //check category slider dir is exists
         if(!storage::disk('public')->exists('category/slider')){
             storage::disk('public')->makeDirectory('category/slider');
         }

         //resize for category slider & upload

         $slider = Image::make($image)->resize(500,333)->stream();
         storage::disk('public')->put('category/slider/'.$imagename,$slider);


        
          
      }else{
          $imagename  = "default.png";
      }

      $category        = new Category();
      $category->name  = $request->name;
      $category->slug  = $slug;
      $category->image = $imagename;
      $category->save();
      Toastr::success('Category Successfully save:','success') ;
       return redirect()->route('admin.category.index');



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        

    

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);// when resource controller without model 
        return view('admin.category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required',
            'image'=> 'mimes:jpeg,bmp,jpg,png'

        ]);
        //get from image

        $image    = $request->file('image');
        $slug     = str_slug($request->name);
        $category = category::find($id);
        if(isset($image)){
            $currentDate = carbon::now()->toDatestring();
            $imagename   = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if(!storage::disk('public')->exists('category')){
                storage::disk('public')->mnakeDirectory('category');
            }
            //delete old category image
            if(storage::disk('public')->exists('category/'.$category->image)){
                storage::disk('public')->delete('category/'.$category->image);
            }

            //resize image for category & upload

            $categoryimage = Image::make($image)->resize(1600,479)->stream();
            storage::disk('public')->put('category/'.$imagename,$categoryimage);

          

           if(!storage::disk('public')->exists('category/slider')){
               Storage::disk('public')->makeDirectory('category/slider');
           }

          //delete old slider image
          if(storage::disk('public')->exists('category/slider/'.$category->image)){
              storage::disk('public')->delete('category/slider/'.$category->image);
          }

          //resize image for category slider & upload
          $slider = Image::make($image)->resize(500,333)->stream();
          storage::disk('public')->put('category/slider/'.$imagename,$slider);


        }else{
            $imagename = $category->image;
        }

        $category->name = $request->name;
        $category->slug = $slug;
        $category->image= $imagename;
        $category->save();
        Toastr::success('Category successfully updated','success');
        return redirect()->route('admin.category.index');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (Storage::disk('public')->exists('category/'.$category->image))
        {
            Storage::disk('public')->delete('category/'.$category->image);
        }

        if (Storage::disk('public')->exists('category/slider/'.$category->image))
        {
            Storage::disk('public')->delete('category/slider/'.$category->image);
        }
        $category->delete();
        Toastr::success('Category Successfully Deleted :)','Success');
        return redirect()->back();

    }
}
