@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
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
    <form role="form" method="POST" action="{{route('admin.addnotice.store')}}" class="validate" id="validate">
      <div class="card-body">
        @csrf
        <input type="hidden" class="form-control" id="title" name="notice_id" autocomplete="off" value="{{ $notice_id}}">
        <div class="row">
          <div class="col-md-4">
            <label for="shift">Class</label>
            @foreach($classes as $key=>$class)
            <div class="form-check">
              <input class="form-check-input class_filter" type="checkbox" name="class_id[]" id="class{{$key}}" value="{{$class->id}}" @foreach($noticefors as $noticefor) {{$noticefor->class_id == $class->id ? 'checked' : ''}} @endforeach>
              <label class="form-check-label" for="class{{$key}}">
                {{$class->name}}
              </label>
            </div>
            @endforeach
          </div>
          <div class="col-md-4">
            <div id="replaceClass"></div>
          </div>
          <div class="col-md-4">
            <div id="replaceSection"></div>
          </div>
        </div>
      </div>
      <div class="card-footer justify-content-between">
        <button type="submit" class="btn btn-info btn-sm text-capitalize">SAVE</button>
      </div>
    </form>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
{{-- <script>
  $().ready(function() {
    $("#validate").validate({
      rules: {
        title : "required",
        description : "required",
      },
      messages: {
        title: " title is required **",
        description: " description is required **",
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
</script> --}}

{{-- <script type="text/javascript">
  $("body").on("change",".shift_filter", function(event){
    Pace.start();
    var selected = new Array();
    $(".shift_filter:checked").each(function () {
      selected.push(this.value);
    });
    // debugger;
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"HTML",
      url:"{{route('admin.getNoticeClassList')}}",
      data:{
        _token: token,
        selected: selected
      },
      success: function(data){
        $('#replaceClass').html(data);
      },
      error: function(event){
        alert("Please reload the page.");
      }
    });
    Pace.stop();
  });
        $("#shift_all").click(function() {
          debugger;
          $("input[type=checkbox]").prop("checked", $(this).prop("checked"));
        });
        $("input[type=checkbox]").click(function() {
          if (!$(this).prop("checked")) {
            $("#shift_all").prop("checked", false);
          }
        });
</script> --}}
{{-- <script type="text/javascript">
  $("body").on("change",".class_filter", function(event){
    Pace.start();
    var shift_id = new Array();
    $(".shift_filter:checked").each(function () {
      shift_id.push(this.value);
    });
    var selected = new Array();
    $(".class_filter:checked").each(function () {
      selected.push(this.value);
    });
    // debugger;
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"HTML",
      url:"{{route('admin.getNoticeSectionList')}}",
      data:{
        _token: token,
        shift_id: shift_id,
        selected: selected
      },
      success: function(data){
        $('#replaceSection').html(data);
      },
      error: function(event){
        alert("Please reload the page.");
      }
    });
    Pace.stop();
  });
</script> --}}
<script type="text/javascript">
  function myFunction()
  {
    document.getElementById("proceed_data").style.visibility = 'hidden';
    document.getElementById("save_data").style.visibility = 'visible';
  }
</script>
<!-- <script type="text/javascript">
  $('#proceed_data').hide();
</script> -->
@endpush