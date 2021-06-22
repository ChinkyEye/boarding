@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize"></h1>
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
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('admin.observationmark.store')}}" id="signup">
      <div class="card-body">
        @csrf
        <input type="hidden" name="student_id" value="{{$student_id}}">
        <input type="hidden" name="classexam_id" value="{{$classexam_id}}">
        <input type="hidden" name="user_slug" value="{{$user_slug}}">
        <input type="hidden" name="exam_slug" value="{{$exam_slug}}">
        @if ($check_data)
        <input type="hidden" name="invoicemark_id" value="{{$invoicemark_id}}">
        {{-- for update observation --}}
          <div class="row">
            {{-- {{dd($groups , $observation_mark_list)}} --}}
            @foreach ($groups as $group => $observ_list)
            <div class="col-md-3">
              <label><b>{{ $group }}</b></label>
              @foreach($observ_list as $key=>$observation)
              {{-- {{dump($observation_mark_list->each->get())}} --}}
              <div class="form-check">
                <input class="form-check-input observation_id" type="radio" name="observation_id[{{$group}}]" id="{{ $observation->id }}" value="{{ $observation->id }}" 
                  @foreach($observation_mark_list as $check_list)
                  {{ $check_list->observation_id == $observation->id ? 'checked' : '' }}
                  @endforeach
                >
                <label class="form-check-label" for="{{ $observation->id }}">
                  <b>{{$observation->remark}}</b> ( {{ $observation->value }} )
                </label>
              </div>
              @endforeach
            </div>
            @endforeach
          </div>
        @else
        {{-- observation create --}}
          <div class="row">
            @foreach ($groups as $group => $observ_list)
            <div class="col-md-3">
              <label><b>{{ $group }}</b></label>
              @foreach($observ_list as $key=>$observation)
              <div class="form-check">
                <input class="form-check-input observation_id" type="radio" name="observation_id[{{$observation->title}}]" id="{{ $observation->id }}" value="{{ $observation->id }}" {{ $key == 0 ? 'checked' : ''}}>
                <label class="form-check-label" for="{{ $observation->id }}">
                  <b>{{$observation->remark}}</b> ( {{$observation->value}} )
                </label>
              </div>
              @endforeach
            </div>
            @endforeach
          </div>
        @endif

      </div>
      <div class="card-footer justify-content-between">
        <button type="submit" class="btn btn-info text-capitalize d-none" data-toggle="tooltip" data-placement="top" title="Save Shift" id="submit">Save</button>
      </div>
    </form>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script type="text/javascript">
  $('.observation_id').click(function(event){
    $("#submit").removeClass('d-none');
  })
</script>
<script>
$().ready(function() {
  $("#signup").validate({
    rules: {
      observation_id: "required",
    },
    messages: {
      observation_id: " observation_id field is required **",
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