@extends('main.school.include.app')
@section('school-content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{$count_list['student']}}</h3>

            <p>Total Students</p>
          </div>
          <div class="icon">
            <i class="fa fa-users"></i>
          </div>
          <a href="{{ route('main.student.index',$school_info->slug) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{$count_list['teacher']}}</h3>

            <p>Total Teachers</p>
          </div>
          <div class="icon">
            <i class="fa fa-users"></i>
          </div>
          <a href="{{ route('main.teacher.index',$school_info->slug) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{$count_list['book']}}</h3>

            <p>Total Books</p>
          </div>
          <div class="icon">
            <i class="fa fa-book"></i>
          </div>
          <a href="{{ route('main.book.index',$school_info->slug) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{$count_list['shift']}}</h3>

            <p>Total Shift</p>
          </div>
          <div class="icon">
            <i class="fa fa-calendar-alt"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection