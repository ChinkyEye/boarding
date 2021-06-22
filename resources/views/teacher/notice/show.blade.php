@extends('teacher.main.app')
@section('content')
<section class="content-header">
</section>
<section class="content">
  <div class="card card-info card-outline">
    @foreach($notices as $notice)
    <div class="card-header">
      <span class="font-weight-bold h4">{{ $notice->title }} </span>
      
      <div class="card-tools">
        <i class="fa {{ $notice->is_active == '1' ? 'fa-check check-css' : 'fa-times cross-css' }}"></i>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md">
          {!! $notice->description !!}
        </div>
      </div>
    </div>
    @endforeach
  </div>
</section>
@endsection