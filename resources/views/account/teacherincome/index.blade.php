@extends('account.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 8, strpos(str_replace('account.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('account.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    <div class="card-header">
      <a href="{{ route('account.salary.create')}}" class="btn btn-sm btn-info text-capitalize" title="Add salary">Add {{ $page }} </a>
      <a href="{{route('account.ledger')}}" class="btn btn-info btn-sm text-capitalize" title="Add Monthly ledger"><i class="fa fa-plus-circle"></i> Monthly</a>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="{{request()->url()}}" data-source-selector="#card-refresh-content"><i class="fas fa-sync-alt"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="bg-dark">
          <tr class="text-center">
            <th width="10">SN</th>
            <th width="100">Teacher</th>
            <th width="100">Salary</th>
            <th width="100">Grade</th>
            <th width="100">Mahangi Vatta</th>
            <th width="100">Durgam Vatta</th>
            <th width="100">Citizen Investment Deduction</th>
            <th width="100">Loan Deduction</th>
            <th width="100">Cloth Amount</th>
            <th width="100">Remarks</th>
            <th width="100">Month</th>
            <th width="150">Created By</th>
            <th width="100">Action</th>
          </tr>
        </thead>              
        @foreach($teachers as $key=>$teacher)             
        <tr class="text-center">
          <td>{{$key+1}}</td>
          <td>{{$teacher->getTeacherUser->name}} {{$teacher->getTeacherUser->middle_name}} {{$teacher->getTeacherUser->last_name}}</td>
          <td>{{$teacher->amount}}</td>
          <td>{{$teacher->grade}}</td>
          <td>{{$teacher->mahangi_vatta}}</td>
          <td>{{$teacher->durgam_vatta}}</td>
          <td>{{$teacher->citizen_investment_deduction}}</td>
          <td>{{$teacher->loan_deduction}}</td>
          <td>{{$teacher->cloth_amount}}</td>
          <td>{{$teacher->remark}}</td>
          @if($teacher->month == 1)
          <td>Baisakh</td>
          @elseif($teacher->month == 2)
          <td>Jestha</td>
          @elseif($teacher->month == 3)
          <td>Asar</td>
          @elseif($teacher->month == 4)
          <td>Shrawan</td>
          @elseif($teacher->month == 5)
          <td>Bhandra</td>
          @elseif($teacher->month == 6)
          <td>Ashoj</td>
          @elseif($teacher->month == 7)
          <td>Kartik</td>
          @elseif($teacher->month == 8)
          <td>Mangsir</td>
          @elseif($teacher->month == 9)
          <td>Poush</td>
          @elseif($teacher->month == 10)
          <td>Magh</td>
          @elseif($teacher->month == 11)
          <td>Falgun</td>
          @elseif($teacher->month == 12)
          <td>Chaitra</td>
          @endif
          <td>{{$teacher->getUser->name}}</td>
          <td>
            <a href="{{ route('account.salary.edit',$teacher->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Update"><i class="fas fa-edit"></i></a>
            <form action="{{ route('account.salary.destroy',$teacher->id) }}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
              @csrf
              @method('DELETE')
              <button class="btn btn-xs btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</section>
@endsection
