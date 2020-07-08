@extends('layouts.backend.app')

@section('title', 'Tag')


@push('css')
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    
@endpush


@section('content')
<div class="container-fluid">
<form action="{{route('admin.post.update',$post->id)}}" method="Post" enctype="multipart/form-data"> 
    @csrf
    @method('put')
{{-- first row  --}}
 <div class="row clearfix">
    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                  Edit Post
                </h2>
          
            </div>
            <div class="body">
           
                    
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="title" class="form-control" name="title" value="{{$post->title}}">
                            <label class="form-label" >Post Title</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image">Featured Image</label>
                        <input type="file" name="image">
                    </div>
                   
                   <div class="form-group">
                       <input type="checkbox" id="publish" class="filled-in" name="status" value="1" {{$post->status== true ?'checked' : '' }} >
                       <label for="publish">Publish</label>
                   </div>
               
                
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                   Categories and Tags
                </h2>
          
            </div>
            <div class="body">
            
              
                    <div class="form-group form-float">
                    <div class="form-line {{$errors->has('categories')?'focused error' : ''}}">
                            <label for="category">Select Category</label>
                            <select name="categories[]" class="form-control show-tick"  multiple>
                                @foreach($categories as $key => $category)
                            <option 
                            @foreach($post->categories as $postcategory)
                               {{$postcategory->id == $category->id ? 'selected' : '' }}
                            @endforeach
                            value="{{$category->id}}" >{{$category->name}}
                            </option>    
                                @endforeach
                            </select>
                        </div>
                    </div>

                    
                    <div class="form-group form-float">
                        <div class="form-line {{$errors->has('tags')?'focused error' : ''}}">
                                <label for="tag">Select tag</label>
                                <select name="tags[]" class="form-control show-tick"  multiple>
                                    @foreach($tags as $key => $tag)
                                <option 
                                @foreach($post->tags as $key => $posttag)
                                    {{$posttag->id == $tag->id ? 'selected' : ''}}
                                @endforeach
                                value="{{$tag->id}}" >{{$tag->name}}
                            </option>
                                 
                                        
                                    @endforeach
                                </select>
                            </div>
                        </div>
    

                  
                {{-- <a class="btn btn-danger m-t-15 waves-effect" href="{{ route('admin.Post.store') }}">BACK</a> --}}
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">SUBMIT</button>
                
            </div>
        </div>
    </div>
</div>

{{-- body part --}}

 <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                   Body
                </h2>
          
            </div>
            <div class="body">
           
            <textarea id="tinymce" name="body" > {{$post->body}}</textarea>
                
            </div>
        </div>
    </div>

</div>
</form>
<!-- #END# Vertical Layout -->
</div>
    
@endsection


@push('js')
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script src="{{ asset('assets/backend/plugins/tinymce/tinymce.js') }}"></script>
<script>
    $(function () {
  

    //TinyMCE
    tinymce.init({
        selector: "textarea#tinymce",
        theme: "modern",
        height: 300,
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons',
        image_advtab: true
    });
    tinymce.suffix = ".min";
    tinyMCE.baseURL ='{{asset('assets/backend/plugins/tinymce')}}';
});
</script>
    
@endpush