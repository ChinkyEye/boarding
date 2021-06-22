@extends('main.main.app')
@section('content')
<?php $page = request()->segment(count(request()->segments()))  ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    @if(count($admins)=='0')
    <p class="text-center text-justify font-italic text-danger pt-2">Please fill out the form properly.This username and password will be provided to  the school principle</p>
   <form role="form" method="POST" action="{{ route('main.admin.store')}}" >
     <div class="card-body">
       @csrf
       <div class="row">
         <input type="hidden" name="school_id" value="{{$school_id}}">
         <div class="form-group col-md-3">
           <label for="name">Name</label>
           <input type="name" class="form-control max" name="name" id="name" placeholder="Enter admin name" autofocus @error('name') is-invalid @enderror" name="name" value="{{ $principal_name}}">
           @error('name')
           <span class="text-danger font-italic" role="alert">
             <strong>{{ $message }}</strong>
           </span>
           @enderror
         </div>
         <div class="form-group col-md-3">
           <label for="email">Email</label>
           <input type="text" class="form-control max" name="email" id="email" placeholder="Enter email" @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
           @error('email')
           <span class="text-danger font-italic" role="alert">
             <strong>{{ $message }}</strong>
           </span>
           @enderror
         </div>
         <div class="form-group col-md-3">
           <label for="password">{{ __('Password') }}</label>
           <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
           @error('password')
           <span class="invalid-feedback" role="alert">
             <strong>{{ $message }}</strong>
           </span>
           @enderror
         </div>
         <div class="form-group col-md-3">
          <label for="password-confirm">{{ __('Confirm Password') }}</label>
          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
         </div>
       </div>
     </div>
     <div class="card-footer justify-content-between"> 
       <button type="submit" class="btn btn-info text-capitalize">Save</button>   
     </div>
   </form>
    @elseif(count($admins)<='1')
    @endif
    
  </div>
  <div class="table-responsive">
     <table class="table table-striped table-bordered table-hover">
       <thead class="bg-dark">
         <tr class="text-center">
           <th>SN</th>
           <th>Admin Name</th>
           <th>Email</th>
           <th>Action</th>
         </tr>
       </thead>
        @foreach($admins as $key => $admin)
       <tr class="text-center">
        <td>1</td>
        <td>{{$admin->name}}</td>
        <td>{{$admin->email}}</td>
        <td>
          <form action="{{ route('main.admin.destroy',$admin->id) }}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
            @csrf
            @method('DELETE')
            <button class="btn btn-xs btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
          </form>
          <a href="{{ route('main.admin.reset',$admin->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Reset Password"><i class="fas fa-key"></i></a>
        </td>
      </tr>
        @endforeach
    </table>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$().ready(function() {
  $("#validate").validate({
    rules: {
      name: "required"
    },
    messages: {
      name: " name field is required **"
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
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#profile-img-tag').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
  $("#image").change(function(){
    readURL(this);
  });
</script>
@endpush