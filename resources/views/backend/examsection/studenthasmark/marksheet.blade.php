@extends('backend.main.app')
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h3>{{$db_student->name}} {{$db_student->middle_name}} {{$db_student->last_name}} | {{$db_student->getUserStudent->getClass->name}}</h3>
      </div>
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item text-capitalize"> Student Name </li>
          <li class="breadcrumb-item active text-capitalize"> Marksheet </li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <button type="button" class="btn btn-info btn-xs hidden-print" onclick="PrintDiv('printDiv')" >PRINT<i class="fa fa-print"></i></button>
  <div class="card card-info">
    <div class="card-body">
      <div id="printDiv">
        <div class="text-center">
          @foreach($settings as $key=>$setting)
          {{-- <img src="{{URL::to('/')}}/backend/images/logo.png" class="img-fluid"> --}}
          <h2 class="text-capitalize">{{$setting->school_name}}</h2>
          <h4>{{$setting->address}}</h4>
          <div>Phone : +977 {{$setting->phone_no}}, Email : {{$setting->email}}, Website : {{$setting->url}} </div>
          @endforeach
        </div>
        <div class="text-center my-4">
          <span class="px-4 py-2 bg-primary rounded h5 font-weight-bold">MARK-SHEET</span>
          <div class="text-capitalize mt-3">
            <u class="h5 font-weight-bold">{{$db_exam->name}}</u>
            <span class="position-absolute mr-4" style="right: 0;"><b>Academic Year:</b> {{$db_student->getUserStudent->getBatch->name}}</span>
          </div>
        </div>
        <table class="table table-borderless table-hover table-sm">
          <tr>
            <td width="200px">
              Student Name
            </td>
            <td>: {{$db_student->name}} {{$db_student->middle_name}} {{$db_student->last_name}}({{$db_student->getUserStudent->student_code}})</td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td width="200px">
              Class
            </td>
            <td>
              : {{$db_student->getUserStudent->getClass->name}}
            </td>
            <td width="200px">
              Roll No / Section
            </td>
            <td>
              : {{$db_student->getUserStudent->roll_no}} / {{$db_student->getUserStudent->getSection->name}}
            </td>
          </tr>
        </table>
        <table class="table table-bordered table-hover table-sm">
          {{-- heads --}}
          <tr class="text-center">
            <th rowspan="2" width="10px" style="vertical-align: middle;">SN</th>
            <th rowspan="2" style="vertical-align: middle;">Subjects</th>
            <th colspan="2" width="10px" style="vertical-align: middle;">Obtained Grade</th>
            <th rowspan="2" width="100px" style="vertical-align: middle;">Final Grade</th>
            <th rowspan="2" width="100px" style="vertical-align: middle;">Grade Point</th>
            <th rowspan="2"  width="100px" style="vertical-align: middle;">Remarks</th>
          </tr>
          <tr class="text-center">
            <th rowspan="1" width="100px">Tr</th>
            <th rowspan="1" width="100px">Pr</th>
          </tr>
          {{-- end head --}}
          {{-- body --}}
          {{-- @foreach ($grouped_class_subject as $key=>$element)
            <tr class="text-center">
              <th>1</th>
              <td class="text-left">{{$key}}</td>
                @foreach($element as $i=>$data)
                  @if ($element->count() == 2)
                    <td>{{$data['id']}}</td>
                  @else
                    <td>{{$data['id']}}</td>
                    <td></td>
                  @endif
                @endforeach
              <td>A</td>
              <td>4.0</td>
              <td colspan="2">Pass</td>
            </tr>
          @endforeach --}}
          @foreach ($grouped_class_subject as $key=>$element)
            <tr class="text-center">
              <th>{{$key+1}}</th>
              <td class="text-left">{{$element->getSubjectOne->name}}</td>
              @if ($element->type_id == 1)
                <td>
                  @if ($element->obtained_mark == 'a')
                    Absent
                  @elseif($element->obtained_mark == '-')
                  -
                  @else
                  {{$element->grade}}
                  @endif
                </td>
                <td></td>
                {{-- 1 < 1 --}}
              @else
                <td></td>
                <td>
                  @if ($element->obtained_mark == 'a')
                  Absent
                  @elseif($element->obtained_mark == '-')
                  -
                  @else
                  {{$element->grade}}
                  @endif
                </td>
              @endif
              <td>
                @if ($element->obtained_mark == 'a')
                Absent
                @elseif($element->obtained_mark == '-')
                -
                @else
                {{$element->grade}}
                @endif
              </td>
              <td>
                {{$element->grade_point}}
              </td>
              <td>
                @forelse ($grades_asc as $grade_remark)
                  @if ($grade_remark->name == $element->grade)
                    {{$grade_remark->remark}}
                  @endif
                @empty
                -
                @endforelse
              </td>
              {{-- <td colspan="2">Pass</td> --}}
            </tr>
            
          @endforeach
          {{-- end body --}}
          {{-- footer --}}
          <tr class="text-center">

            <th colspan="4" class="text-right border-right-0">Average grade:</th>
            <th class="border-left-0 border-right-0">
              @php
                // $gpa = '1.1';
                $gpa = number_format(round(($grouped_class_subject->sum('grade_point')) / ($grouped_class_subject->count() + 1) , 2) , 2, '.', '');
                $pre_grade_point_value = 0;
              @endphp
              @foreach ($grades_asc as $loop=>$value_grade) 
                {{-- @if ($gpa > max || $gpa >= min) --}}

                {{-- @if (4.00 <= 1.0 && 4.00 > 0.0) --}}
                {{-- @if (4.00 <= 2.0 && 4.00 > 1.0) --}}
                {{-- @if (4.00 <= 3.0 && 4.00 > 2.0) --}}
                {{-- @if (4.00 <= 4.0 && 4.00 > 3.0) --}}
                @if ($gpa <= $value_grade->grade_point)
                  @if ($gpa > $pre_grade_point_value)
                      {{$value_grade->name}}
                  @endif
                @endif

                @php $pre_grade_point_value = $value_grade->grade_point; @endphp
              @endforeach
              {{-- @if ($gpa == 0)
              Fail
              @endif --}}
            </th>
            <th colspan="2" class="text-left border-left-0">GPA : {{ $gpa }}</th>
            {{-- <th colspan="1" class="text-left border-left-0">Remarks : s</th> --}}
          </tr>
        </table>
        @if ($observations->isNotEmpty())
          <div class="text-center my-4">
            <span class="px-4 py-2 bg-primary rounded h5 font-weight-bold">Observation</span>
          </div>
        @endif
        <div class="row">
          @foreach($observations as $key=>$observation)
          <div class="col-6 border">
            <span>{{$observation->getObservation->title}}</span>
            <span> : <b>{{$observation->getObservation->remark}}</b></span>
          </div>
          @endforeach
          {{-- <div class="col-6 border">
            <span>Discipline</span>
            <span> : <b>A</b></span>
          </div> --}}
        </div>
        <div class="text-center my-4">
          <span class="px-4 py-2 bg-primary rounded h5 font-weight-bold">Grade System</span>
        </div>
        <div class="row">
          <table class="table table-bordered table-hover table-sm">
            {{-- heads --}}
            <thead class="bg-dark text-center th-sticky-top">
              <tr>
                <th width="10">SN</th>
                <th width="10">Percentage</th>
                <th width="10">Grade</th>
                <th width="10">Grade Point</th>
              </tr>
            </thead>
            @foreach ($grades as $key=>$grade)
              <tr class="text-center">
                <th>{{$key+1}}</th>
                <td>{{$grade->max}}% - {{$grade->min}}%</td>
                <td>{{$grade->name}}</td>
                <td>
                  {{$grade->grade_point}}
                </td>
              </tr>
            @endforeach
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@push('javascript')

@endpush