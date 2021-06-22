@extends('main.main.app')
@section('content')
<section>
	<div class="card rounded-0 bg-light shadow-none {{-- maximized-card --}}">
			@include('main.school.include.header')
		<div class="card-body">
			@yield('school-content')
		</div>
	</div>
</section>
@endsection