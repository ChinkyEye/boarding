@extends('backend.main.app')
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">
        <h1 class="text-capitalize">Welcome {{Auth::user()->name}}</h1>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="container-fluid">
      @if(Auth::user()->getBatch)
        ({{Auth::user()->getBatch->name}})
      @else
       <div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
         <div class="modal-dialog"  role="document">
           <div class="modal-content">
             <div class="modal-header bg-info">
               <h4 class="modal-title text-capitalize">Please insert Batch to continue</h4>
               {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
               </button> --}}
             </div>
               <div class="modal-body" >
                 <div class="form-group">
                   <label for="return_date">This link will take to profile Page</label>
                   <a href="{{ route('admin.profile.index') }}" class="small-box-footer">Click Here <i class="fas fa-arrow-circle-right"></i></a>
                 </div>
               </div>
               <div class="modal-footer justify-content-between">
               </div>
           </div>
         </div>
       </div>
      @endif

    <div class="row">
      <div class="col-lg-3 col-6">
        <div class="small-box bg-purple">
          <div class="inner">
            <h3>{{ $count_list['shift'] }}</h3>

            <p>Total Shift</p>
          </div>
          <div class="icon">
            <i class="fa fa-calendar-alt"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-indigo">
          <div class="inner">
            <h3>{{ $count_list['sclass'] }}</h3>

            <p>Total Class</p>
          </div>
          <div class="icon">
            <i class="fa fa-calendar-alt"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-blue">
          <div class="inner">
            <h3>{{ $count_list['section'] }}</h3>

            <p>Total Section</p>
          </div>
          <div class="icon">
            <i class="fa fa-calendar-alt"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ $count_list['book'] }}</h3>

            <p>Total Books</p>
          </div>
          <div class="icon">
            <i class="fa fa-book"></i>
          </div>
          <a href="{{ route('admin.book.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $count_list['student'] }}</h3>

            <p>Total Students</p>
          </div>
          <div class="icon">
            <i class="fas fa-user-tie"></i>
          </div>
          <a href="{{ route('admin.student.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-teal">
          <div class="inner">
            <h3>{{ $count_list['student_present'] }}</h3>

            <p>Today Students Present</p>
          </div>
          <div class="icon">
            <i class="fa fa-users"></i>
          </div>
          <a href="{{ route('admin.student-attendance.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{ $count_list['teacher'] }}</h3>

            <p>Total Teachers</p>
          </div>
          <div class="icon">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
          <a href="{{ route('admin.teacher.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-teal">
          <div class="inner">
            <h3>{{ $count_list['teacher_present'] }}</h3>

            <p>Today Teachers Present </p>
          </div>
          <div class="icon">
            <i class="fa fa-users"></i>
          </div>
          <a href="{{ route('admin.teacher-attendance.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      
    </div>
  </div>

</section>

@endsection
@push('javascript')
<script type="text/javascript">
    $(window).on('load', function() {
        $('#myModal').modal('show');
    });
</script>



@endpush