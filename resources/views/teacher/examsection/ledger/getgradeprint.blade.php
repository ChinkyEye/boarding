@extends('teacher.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/print.main.css">
@endpush
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('teacher.','',Route::currentRouteName()), ".")); ?>
<section class="content-header"></section>
<section class="content">
  <div class="card">
    <div class="card-body">
        {{-- <button class="btn btn-xs btn-info rounded-0 float-right" id="data" data-id="{{$user_id}}" data-category="mark" onclick="PrintDiv('printTable')">Print me</button> --}}
        <div class="float-right">
          <button type="button" class="btn btn-xs btn-info rounded-0" onclick="PrintDiv('printTable')">Print me</button>
        </div>
      <div class="w-100  table-responsive" id="printTable">
        <small>
          <div class="text-center mb-3">
            <h4 class="mb-0">{{$exams_name->name}}</h4>
            <h5 class="mb-0 text-uppercase">Marks Ledger Search results</h5> 
            @if($shifts_name)  <b>Shift:</b> {{$shifts_name->name}} | @endif 
            <b> Class:</b>{{$classes_name->name}} 
            @if($sections_name) | <b>Section:</b> {{$sections_name->name}}  @endif 
          </div>
        </small>
        <table class="table table-bordered table-hover position-relative m-0">
          <thead class="bg-dark text-center th-sticky-top">
            <tr>
              <th width="10">Sn</th>
              <th width="10">Subject</th>
              <th width="10">Full</th>
              <th width="10">Pass</th>
              <th width="10">Theory/Practical</th>
              <th width="100">Compulsory/Optional</th>
              <th width="10">Obtained Mark</th>
              <th width="10">Grade</th>
              <th width="10">Grade Point</th>
            </tr>
          </thead>
          @foreach($subjects as $key=>$data)             
          <tr class="text-center">
            <td class="text-left">{{$key+1}} </td>
            <td class="text-left"> 
              {{ $data->getSubject->name }}
            </td>
            <td>{{ $data->getClassMark->full_mark }}</td>
            <td> {{ $data->getClassMark->pass_mark }}</td>
            <td> {{ $data->type_id === 1 ? "Theory" :"Practical" }}</td>
            <td> {{ $data->getSubject->compulsory_optional == 2 ? 'Opt' : 'Compulsory'  }}</td>
            <td>
              <input type="text" class="form-control p-0 h-75 w-100 border-0 bg-transparent text-center" placeholder="Marks" value="{{ $data->obtained_mark  }}" readonly >
              @error('obtained_mark')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </td>
            <td>
              <input type="text" class="w-100 border-0 bg-transparent text-center" name="grade[]" id="grade_{{$key}}" value="{{ $data->grade }}" readonly>
            </td>
            <td>
              <input type="text" class="w-100 border-0 bg-transparent text-center" name="grade_point[]" id="grade_point_{{$key}}" value="{{ $data->grade_point}}" readonly>
            </td>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script>
  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
  });
</script>
{{-- <script type="text/javascript">
  function PrintDiv(divName) {
    var button = document.getElementById('data');
    var data_id = button.getAttribute('data-id');
    var data_category = button.getAttribute('data-category');
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"HTML",
      url:"{{route('teacher.mark.count')}}",
      data:{
        _token: token,
        user_id: data_id,
        category: data_category
      },
      success: function(data){
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
        // alert('printed');
        // $('#replaceSection').html(data);
      },
      error: function(event){
        alert("Please reload the page.");
      }
    });

    console.log(data_id,data_category);
    // console.log(id);

    // var data_id = $(this).data("id");
    // console.log($(this));
  }
</script> --}}
{{-- <script>
  (function () {

         var beforePrint = function () {
             alert('Functionality to run before printing.');
         };

         var afterPrint = function () {
             alert('Functionality to run after printing');
         };

         if (window.matchMedia) {
             var mediaQueryList = window.matchMedia('print');

             mediaQueryList.addListener(function (mql) {
                 //alert($(mediaQueryList).html());
                 if (mql.matches) {
                     beforePrint();
                 } else {
                     afterPrint();
                 }
             });
         }

         window.onbeforeprint = beforePrint;
         window.onafterprint = afterPrint;

     }());

</script> --}}

{{-- <script type="text/javascript" src="{{URL::to('/')}}/backend/js/jquery.printPage.js"></script> --}}
@endpush