@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/print.main.css">
@endpush
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header"></section>
<section class="content">
  <div class="card card-info">
    <div class="float-right">
      <button type="button" class="btn btn-xs btn-info rounded-0" onclick="PrintDiv('printTable')">Print me</button>
    </div>
    {{-- <form action="{{route('admin.student.count')}}" method="POST" class="d-inline-block">
      {{ csrf_field() }}
      <input type="hidden" name="user_id" id="user_id"  value="{{$user_id}}">
      <input type="hidden" name="category" id="category"  value="mark">
      <input type="submit" name="submit" value="Print" class="btn btn-info">
    </form> --}}
    {{-- <button class="btn btn-xs btn-info rounded-0 float-right" id="data" data-id="{{$user_id}}" data-category="mark" onclick="PrintDiv('printTable')">Print me</button> --}}

      <div id='printTable'>
         <table class="table table-bordered table-hover position-relative m-0">
           <thead class="bg-dark text-center th-sticky-top inverted">
             <tr>
               <th width="10">Sn</th>
               <th width="10">Subject</th>
               <th width="10">Full</th>
               <th width="10">Pass</th>
               <th width="10">Theory/Practical</th>
               <th width="100">Compulsory/Optional</th>
               <th width="10">Obtained Mark</th>
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
             <td> {{ $data->getSubject->theory_practical === 3 ? "Both" : ($data->getSubject->theory_practical ===1 ? "Theory" : "Practical") }}</td>
             <td> {{ $data->getSubject->compulsory_optional == 2 ? 'Opt' : 'Compulsory'  }}</td>
             <td>
               <input type="text" class="form-control p-0 h-75 w-100 border-0 bg-transparent text-center" placeholder="Marks" value="{{ $data->obtained_mark  }}" readonly >
               @error('obtained_mark')
               <span class="text-danger font-italic" role="alert">
                 <strong>{{ $message }}</strong>
               </span>
               @enderror
             </td>
           </tr>
           @endforeach
         </table>
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
      url:"{{route('admin.student.count')}}",
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