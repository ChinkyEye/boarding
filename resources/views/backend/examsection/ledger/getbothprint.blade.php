@extends('backend.main.app')
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
      <input type="hidden" name="category" id="category"  value="grade">
      <input type="submit" name="submit" value="Print" class="btn btn-info">
    </form> --}}
    {{-- <button class="btn btn-xs btn-info rounded-0 float-right" onclick="PrintDiv('printTable')">Print me</button> --}}
    <div id='printTable'>
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
            <th width="10">%</th>
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
          <td>
            <input type="text" class="w-100 border-0 bg-transparent text-center" name="percentage[]" id="percentage{{$key}}" value="{{$data->percentage}}" readonly>
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
</section>
@endsection
@push('javascript')
<script>
  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
  });
</script>
@endpush