@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="text-capitalize">{{ $page }} Edit</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    <form role="form" method="POST" action="{{ route('admin.book.update',$books->id)}}" enctype="multipart/form-data">
      <div class="card-body">
        @method('PATCH')
        @csrf
        <div class="row">
          <div class="form-group col-md-12">
            <label for="multiple" class="control-label">Class</label>
            <span> : {{$books->getClass->name}}</span>
          </div>
          <div class="form-group col-md-4">
            <label for="address">Book Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Enter Book Name" autocomplete="off" value="{{$books->name}}">
            @error('name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="address">Book No <span class="text-danger">*</span></label>
            <input type="text" name="book_no" class="form-control" id="book_no" placeholder="Enter Book No" autocomplete="off" value="{{$books->book_no}}">
            @error('book_code')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
           <div class="form-group col-md-4">
            <label for="quantity">Quantity<span class="text-danger">*</span></label>
            <input type="text" name="quantity" class="form-control" id="quantity" placeholder="Enter Quantity" autocomplete="off" value="{{$books->quantity}}">
            @error('quantity')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          
          <div class="form-group col-md-4">
            <label for="multiple" class="control-label">Subject<span class="text-danger">*</span></label>
            <select class="form-control" name="subject_id" id="subject_data" >
              <option value="">Select Your Subject</option>
              @foreach ($subjects as $key => $subject)
              <option value="{{ $subject->id }}" {{$books->subject_id == $subject->id ? 'selected' : ''}}> 
                {{$subject->name}}
              </option>
              @endforeach   
            </select>
            @error('subject_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
           <div class="form-group col-md-4">
            <label for="address">Publisher<span class="text-danger">*</span></label>
            <input type="text" name="publisher" class="form-control" id="publisher" placeholder="Enter Publisher" autocomplete="off" value="{{$books->publisher}}">
            @error('publisher')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="auther">Author<span class="text-danger">*</span></label>
            <input type="text" name="auther" class="form-control" id="auther" placeholder="Enter Auther" autocomplete="off" value="{{$books->auther}}">
            @error('auther')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Update</button>
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
      first_name: "required",
      last_name: "required",
    },
    messages: {
      first_name: " first name field is required **",
      last_name: " last name field is required **",
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
    var data_id = $(event.target).val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{ route('admin.getSubjectList')}}",
      data:{
        _token: token,
        data_id: data_id
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