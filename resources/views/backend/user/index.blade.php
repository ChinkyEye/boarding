@extends('backend.main.app')
@push('style')
@endpush
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }} Create</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Create</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
   <form role="form" method="POST" action="{{ route('admin.user.store')}}" >
     <div class="card-body">
       @csrf
       <div class="row">
         {{-- <input type="hidden" name="school_id" value="{{$school_id}}"> --}}
         <div class="form-group col-md-3">
           <label for="name">FirstName</label>
           <input type="name" class="form-control max" name="name" id="name" placeholder="Enter admin name" autofocus @error('name') is-invalid @enderror" name="name" >
           @error('name')
           <span class="text-danger font-italic" role="alert">
             <strong>{{ $message }}</strong>
           </span>
           @enderror
         </div>
         <div class="form-group col-md-3">
           <label for="middle_name">MiddleName</label>
           <input type="text" class="form-control max" name="middle_name" id="middle_name" placeholder="Enter middle_name" @error('middle_name') is-invalid @enderror" name="middle_name" value="{{ old('middle_name') }}">
         </div>
         <div class="form-group col-md-3">
           <label for="last_name">LastName</label>
           <input type="text" class="form-control max" name="last_name" id="last_name" placeholder="Enter last_name" @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}">
           @error('last_name')
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
         <div class="form-group col-md-6">
           <label for="user_type">Select User Type</label>
           <small class="text-danger mr-4">*</small>
           <select class="form-control" name="user_type" id="user_type">
             <option value="">Select Your User Type</option>
             <option value="5">Librarian</option>
             <option value="6">Accountant</option>
           </select>
           @error('user_type')
           <span class="text-danger font-italic" role="alert">
             <strong>{{ $message }}</strong>
           </span>
           @enderror
         </div>
       </div>
     </div>
     <div class="card-footer justify-content-between"> 
       <button type="submit" class="btn btn-info text-capitalize">Save</button>   
     </div>
   </form>
  </div>
</section>
@endsection
@push('javascript')


@endpush