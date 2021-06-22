@extends('teacher.main.app')
@section('content')
<?php $page = "Remainder"; ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="text-capitalize">{{ $page }} </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('teacher.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} </li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info card-outline">
    @foreach($remainders as $remainder)
    <div class="card-header">
      <h4>{{ $remainder->title }}</h4>
    </div>
    <div class="card-body">
      {!! $remainder->description !!}
    </div>
    @endforeach
  </div>
</section>
@endsection