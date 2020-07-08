@extends('layouts.backend.app')

@section('title', 'Tag')


@push('cs')
    
@endpush


@section('content')
 <!-- Vertical Layout -->
 <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                   Add New Category
                </h2>
          
            </div>
            <div class="body">
            <form action="{{route('admin.category.store')}}" method="post" enctype="multipart/form-data">
                @csrf
              
                    <label for="email_address">Category Name</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="name" class="form-control" name="name">
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="file" name="image">
                    </div>
                   
                    <br>
                <a class="btn btn-danger m-t-15 waves-effect" href="{{ route('admin.category.index') }}">BACK</a>
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">SUBMIT</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- #END# Vertical Layout -->
    
@endsection


@push('js')
    
@endpush