@extends('main.school.include.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/print.main.css">
@endpush
@section('school-content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="text-capitalize">{{ $page }} {!! $detail !!} </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('main.school.info.index',$school_info->slug) }}">{{$school_info->school_name}}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('main.studenthasmark.ledger.view',[$school_info->slug]) }}">Exam</a></li>
          <li class="breadcrumb-item active text-capitalize"><a href="{{ route('main.student.show',[$school_info->slug,$std_slug]) }}" target="_blank">{{ $page }}</a></li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    {{-- <form action="{{route('admin.student.count')}}" method="POST" class="d-inline-block">
      {{ csrf_field() }}
      <input type="hidden" name="user_id" id="user_id"  value="{{$user_id}}">
      <input type="hidden" name="category" id="category"  value="mark">
      <input type="submit" name="submit" value="Print" class="btn btn-info">
    </form> --}}
    <div class="float-right">
      <button type="button" class="btn btn-xs btn-info rounded-0" onclick="PrintDiv('printTable')">Print me</button>
    </div>
    {{-- <button class="btn btn-xs btn-info rounded-0 float-right" id="data" data-id="{{$user_id}}" data-category="mark" onclick="PrintDiv('printTable')">Print me</button> --}}
      <div id='printTable'>
        <p class="d-none print-1 font-weight-bold">{{ $page_print }} {!! $detail !!} <br><br></p>
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
             <td>{{$key+1}} </td>
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
  <p class="font-weight-bold mb-0"><span class="text-danger">*</span> '<span class="text-danger">a</span>' is for Absent and '<span class="text-danger">-</span>' for no mark.</p>
</section>
@endsection
@push('javascript')
{{-- <script type="text/javascript">
  function PrintDiv(divName) {
    var button = document.getElementById('data');
    var data_id = button.getAttribute('data-id');
    var data_category = button.getAttribute('data-category');
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"HTML",
      url:"{{route('main.student.count')}}",
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
      },
      error: function(event){
        alert("Please reload the page.");
      }
    });

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