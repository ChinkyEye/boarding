@extends('library.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 8, strpos(str_replace('library.','',Route::currentRouteName()), ".")); ?>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('library.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('library.book.store')}}" class="signup" id="signup">
      <div class="card-body">
        @csrf
        <div class="row">
          <div class="form-group col-md-4">
            <label for="address">Book Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Enter Book Name" autocomplete="off" value="{{ old('name') }}" autofocus>
            @error('name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="address">Book No <span class="text-danger">*</span></label>
            <input type="text" name="book_no" class="form-control" id="book_no" placeholder="Enter Book No" autocomplete="off" value="{{ old('book_no') }}">
            @error('book_code')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>

           <div class="form-group col-md-4">
            <label for="quantity">Quantity<span class="text-danger">*</span></label>
            <input type="text" name="quantity" class="form-control" id="quantity" placeholder="Enter Quantity" autocomplete="off" value="{{ old('quantity') }}">
            @error('quantity')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="class_data" class="control-label col-md-3">Class <span class="text-danger">*</span></label>
            <select class="form-control" name="class_id" id="class_data">
              <option value="">Select Your Class</option>
              @foreach ($classes as $key => $class)
              <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : ''}}> 
                {{$class->name}}
              </option>
              @endforeach    
            </select>
            @error('class_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="subject_data" class="control-label col-md-3">Subject<span class="text-danger">*</span></label>
            <select class="form-control" name="subject_id" id="subject_data" >
              <option value="">Select Your Subject</option>
                
            </select>
            @error('subject_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
           <div class="form-group col-md-6">
            <label for="address">Publisher<span class="text-danger">*</span></label>
            <input type="text" name="publisher" class="form-control" id="publisher" placeholder="Enter Publisher" autocomplete="off" value="{{ old('publisher') }}">
            @error('publisher')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="auther">Author<span class="text-danger">*</span></label>
            <input type="text" name="auther" class="form-control" id="auther" placeholder="Enter Author" autocomplete="off" value="{{ old('auther') }}">
            @error('auther')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card-footer justify-content-between">
        <button type="submit" class="btn btn-info text-capitalize" data-toggle="tooltip" data-placement="top" title="Save Book">Save</button>
      </div>
    </form>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$().ready(function() {
  $("#signup").validate({
    rules: {
      name: "required"
      book_no: "required"
      class_id: "required"
      subject_id: "required"
      publisher: "required"
      auther: "required"
      quantity: "required"
    },
    messages: {
      name: " name field is required **"
      book_no: " book no field is required **"
      class_id: " class field is required **"
      subject_id: " subject field is required **"
      publisher: " publisher field is required **"
      auther: " auther field is required **"
      quantity: " quantity field is required **"
    },
    highlight: function(element) {
     $(element).css('background', '#ffdddd');
     $(element).css('border-color', 'red');
    },
    unhighlight: function(element) {
     $(element).css('background', '#ffffff');
     $(element).css('border-color', 'green');
    }
  });
});
</script>
<script type="text/javascript">
  $("body").on("change","#class_data", function(event){
    Pace.start();
    var class_id = $('#class_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{ route('library.getSubjectList')}}",
      data:{
        _token: token,
        class_id: class_id
      },
      success: function(response){
        $('#subject_data').html('');
        $('#subject_data').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#subject_data').append('<option value='+val.id+'>'+val.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
        Pace.stop();
      }
    });
  });
</script>
@endpush