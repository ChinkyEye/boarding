@extends('backend.main.app')
@push('style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
@endpush
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="text-capitalize">Class {{ $page }} Subject</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize"><a href="{{route('admin.class.index')}}">Class</a> </li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} </li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('admin.subject.store')}}" class="validate" id="validate">
      @csrf
      <input type="hidden" name="class_id" value="{{$class_id}}">
      <div class="card-body">
        <div class="row">
          <div class="form-group col-md">
            <label for="name">Name</label>
            <input type="text"  class="form-control max" id="name" placeholder="Enter name for the subject" name="name" autocomplete="off" value="{{ old('name') }}" autofocus>
            @error('slug')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="subject_code">Subject Code</label>
            <input type="text"  class="form-control max" id="subject_code" placeholder="Enter the subject code" name="subject_code"  autocomplete="off" value="{{ old('subject_code') }}">
            @error('subject_code')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="credit_hour">Credit Hour</label>
            <input type="text"  class="form-control max" id="credit_hour" placeholder="Enter the credit hour" name="credit_hour"  autocomplete="off" value="{{ old('credit_hour') }}">
            @error('credit_hour')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="theory_practical">Theory/Practical:</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="theory_practical" id="theory" value="1" {{ old('theory_practical') == 'theory' ? 'checked' : ''}} checked>
              <label class="form-check-label" for="theory">
                Theory
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="theory_practical" id="practical" value="2" {{ old('theory_practical') == 'practical' ? 'checked' : ''}}>
              <label class="form-check-label" for="practical">
                Practical
              </label>
            </div>
            {{-- <div class="form-check">
              <input class="form-check-input" type="radio" name="theory_practical" id="both" value="3" {{ old('theory_practical') == 'both' ? 'checked' : ''}}>
              <label class="form-check-label" for="both">
                Both
              </label>
            </div> --}}
            @error('theory_practical')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="compulsory_optional">Compulsory/Optional:</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="compulsory_optional" id="compulsory" value="1" {{ old('compulsory_optional') == 'compulsory' ? 'checked' : ''}} checked>
              <label class="form-check-label" for="compulsory">
                Compulsory
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="compulsory_optional" id="optional" value="2" {{ old('compulsory_optional') == 'optional' ? 'checked' : ''}}>
              <label class="form-check-label" for="optional">
                Optional
              </label>
            </div>
            @error('compulsory_optional')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Save Subject">Save Subject</button>
      </div>
    </form>
  </div>
  <div class="card">
    <div class="table-responsive">
     <table class="table table-striped table-bordered table-hover m-0">
       <thead class="bg-dark">
         <tr class="text-center">
           <th width="10">SN</th>
           <th class="text-left">Name</th>
           <th width="150">Subject Code</th>
           <th width="150">Credit Hour</th>
           <th width="150">Theory/Practical</th>
           <th width="150">Compulsory/Optional</th>
           <th width="150">Created By</th>
           <th width="10">Status</th>
           <th width="10">Sort</th>
           <th width="100">Action</th>
         </tr>
       </thead>
       @foreach($subjects as $key=>$subject)
       <tr class="text-center" data-toggle="tooltip" data-placement="top" title="{{ $subject->is_active == '1' ? 'This data is published':' This data is not published'}}"   style="background-color: {{$subject->is_active == '1' ? '#cde0ba' : '#eccdcd'}}">
         <td>{{$key+1}}</td>
         <td class="text-left">{{$subject->name}}</td>
         <td>{{$subject->subject_code}}</td>
         <td>{{$subject->credit_hour}}</td>
         @if($subject->theory_practical == 1)
         <td>Theory</td>
         @elseif($subject->theory_practical == 2)
         <td>Practical</td>
         @elseif($subject->theory_practical == 3)
         <td>Both</td>
         @endif
         @if($subject->compulsory_optional == 1)
         <td>Compulsory</td>
         @elseif($subject->compulsory_optional == 2)
         <td>Optional</td>
         @endif
         <td>{{$subject->getUser->name}}</td>
         <td class="text-center">
           <a href="{{ route('admin.subject.active',$subject->id) }}" data-toggle="tooltip" data-placement="top" title="{{ $subject->is_active == '1' ? 'Click to deactivate' : 'Click to activate' }}">
             <i class="fa {{ $subject->is_active == '1' ? 'fa-check check-css':'fa-times cross-css'}}"></i>
           </a>
         </td>
         <td>
           <p id="main{{$subject->id}}" ids="{{$subject->id}}" class="text-center sort mb-0" page="subject" contenteditable="plaintext-only" url="{{route('admin.subject.sort',$subject->id) }}">{{$subject->sort_id}}</p>
         </td>
         <td class="text-center">
           {{-- <a href="{{ route('admin.subject.show',$subject->id) }}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" data-placement="top" title="View Detail"><i class="fa fa-eye"></i></a> --}}
           <a href="{{ route('admin.subject.edit',$subject->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Update Subject"><i class="fas fa-edit"></i></a>
           <form action="{{ route('admin.subject.destroy',$subject->id) }}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
             @csrf
             @method('DELETE')
             <button class="btn btn-xs btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
           </form>
         </td>
       </tr>
       @endforeach              
     </table>
   </div>
  </div>
</section>
@endsection
@push('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$().ready(function() {
  $("#validate").validate({
    rules: {
      name: {
        required: true,
        minlength: 2
      },
      subject_code: "required",
      theory_practical: "required",
      compulsory_optional: "required"
    },
    messages: {
      name: {
        required: "Please enter a Subject",
        minlength: "Subject must consist of at least 2 characters"
      },
      subject_code: " Subject Code is required",
      theory_practical: " Theory/Practical is required",
      compulsory_optional: " Compulsory/Optional is required"
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
@endpush