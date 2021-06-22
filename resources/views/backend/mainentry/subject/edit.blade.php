@extends('backend.main.app')
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
          <li class="breadcrumb-item active text-capitalize">{{ $page}}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    @foreach($subjects as $subject)
    <form role="form" method="POST" id="validate" class="validate" action="{{route('admin.subject.update',$subject->id)}}">
      <div class="card-body">
        @csrf
        @method('PATCH')
        <div class="row">
          <div class="form-group col-md">
            <label for="name">Name</label>
            <input type="text"  class="form-control max" id="name" placeholder="Enter name for the subject" name="name" autocomplete="off" value="{{ $subject->name }}" autofocus>
            @error('name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="subject_code">Subject Code</label>
            <input type="text"  class="form-control max" id="subject_code" placeholder="Enter the subject code" name="subject_code"  autocomplete="off" value="{{ $subject->subject_code }}">
            @error('subject_code')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="credit_hour">Credit Hour</label>
            <input type="text"  class="form-control max" id="credit_hour" placeholder="Enter the credit hour" name="credit_hour"  autocomplete="off" value="{{ $subject->credit_hour }}">
            @error('credit_hour')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          {{-- <div class="form-group col-md">
            <label for="theory_practical">Theory/Practical:</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="theory_practical" id="theory" value="1" {{ $subject->compulsory_optional == 'theory' ? 'checked' : ''}} checked>
              <label class="form-check-label" for="theory">
                Theory
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="theory_practical" id="practical" value="2" {{ $subject->theory_practical == 'practical' ? 'checked' : ''}}>
              <label class="form-check-label" for="practical">
                Practical
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="theory_practical" id="both" value="3" {{ $subject->theory_practical  == 'both' ? 'checked' : ''}}>
              <label class="form-check-label" for="both">
                Both
              </label>
            </div>
            @error('theory_practical')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div> --}}
          {{-- <div class="form-group col-md">
            <label for="compulsory_optional">Compulsory/Optional:</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="compulsory_optional" id="compulsory" value="1" {{ $subject->compulsory_optional == 'compulsory' ? 'checked' : ''}} checked>
              <label class="form-check-label" for="compulsory">
                Compulsory
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="compulsory_optional" id="optional" value="2" {{ $subject->compulsory_optional == 'optional' ? 'checked' : ''}}>
              <label class="form-check-label" for="optional">
                Optional
              </label>
            </div>
            @error('compulsory_optional')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div> --}}
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