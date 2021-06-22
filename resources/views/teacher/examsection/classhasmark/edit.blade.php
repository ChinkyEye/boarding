@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="text-capitalize">You are managing</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}  Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    @foreach($classhasmarks as $exam)
    <form role="form" method="POST" action="{{ route('admin.classhasmark.update',$exam->id)}}">
      <div class="card-body">
        @method('PATCH')
        @csrf
         <div class="row">
           <div class="form-group col-sm-4">
             <label for="multiple" class="control-label"> Select Subject</label>
             <select class="form-control" name="subject_id" id="filter_shift">
               <option value=" ">Select Your Subject</option>
               @foreach ($subjects as $key => $subject)
               <option value="{{ $subject->id }}" {{ $subject->id == $exam->subject_id ? 'selected' : ''}}> 
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
           <div class="form-group col-md-2">
             <label for="full_mark">Full mark<span class="text-danger">*</span></label>
             <input type="text" class="form-control" id="full_mark" name="full_mark" autocomplete="off" value="{{ $exam->full_mark }}">
             @error('full_mark')
             <span class="text-danger font-italic" role="alert">
               <strong>{{ $message }}</strong>
             </span>
             @enderror
           </div>
           <div class="form-group col-md-2">
             <label for="pass_mark">Pass Mark<span class="text-danger">*</span></label>
             <input type="text" class="form-control" id="pass_mark" name="pass_mark" autocomplete="off" value="{{ $exam->pass_mark }}">
             @error('pass_mark')
             <span class="text-danger font-italic" role="alert">
               <strong>{{ $message }}</strong>
             </span>
             @enderror
           </div>
           <div class="form-group col-md-2">
             <label for="room">Room<span class="text-danger">*</span></label>
             <input type="text" class="form-control" id="room" name="room" autocomplete="off" value="{{ $exam->room }}">
             @error('room')
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
    @endforeach
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/moment.min.js"></script>
<script src="{{URL::to('/')}}/backend/js/tempusdominus-bootstrap-4.js"></script>
<script>
    $('#start_time').datetimepicker({
      format: 'LT'
    })
</script>
<script>
    $('#end_time').datetimepicker({
      format: 'LT'
    })
</script>
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script>
  $('#result_date').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 10
  });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$().ready(function() {
  $("#validate").validate({
    rules: {
      name: "required",
      subject_code: "required",
      theory_practical: "required",
      compulsory_optional: "required"
    },
    messages: {
      name: " name field is required **",
      subject_code: " subject_code field is required **",
      theory_practical: " theory_practical field is required **",
      compulsory_optional: " compulsory_optional field is required **"
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
<script>
  $(document).ready(function() {
      $('.js-multiple').select2();
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endpush