@extends('backend.main.app')
@push('style')
  <link rel="stylesheet" href="{{URL::to('/')}}/backend/css/print.main.css">
@endpush
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <span class="h3">
          {{$homeworks->getTeacher->f_name}} {{$homeworks->getTeacher->m_name}} {{$homeworks->getTeacher->l_name}}
        </span>
          <span class="position-absolute badge badge-info">({{$homeworks->getShift->name}} | {{$homeworks->getClass->name}} | {{$homeworks->getSection->name}})</span>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize"><a href="{{ route('admin.homework.index') }}">{{ $page }}</a> </li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <button type="button" class="btn btn-info btn-xs rounded-0" onclick="PrintDiv('printDiv')">PRINT <i class="fa fa-print"></i></button>
  <div class="card card-info-outline" id="printDiv">
    <div class="card-body">
      {!! $homeworks->description !!}
    </div>
  </div>
</section>
@endsection