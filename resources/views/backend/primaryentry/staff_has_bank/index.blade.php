@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}  Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    @if(count($staffhasbanks)=='0')
    <form role="form" method="POST" action="{{route('admin.staffhasbank.store')}}"  class="validate form-horizontal" id="validate">
      @csrf
      <input type="hidden" name="teacher_id" value="{{$teacher_id}}" id="teacher_data">
      <div class="card-body">
        <div class="row">
          <div class="form-group col-md">
            <label for="bank_name">Bank Name:</label>
            <input type="text"  class="form-control max" id="bank_name" placeholder="Enter Bank Name" name="bank_name"   autocomplete="off" value="{{ old('bank_name') }}" autofocus>
            @error('bank_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="account_no">Bank Account:</label>
            <input type="text"  class="form-control max" id="account_no" placeholder="Enter Bank Account Number" name="account_no"   autocomplete="off" value="{{ old('account_no') }}" autofocus>
            @error('account_no')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="bank_address">Bank Address:</label>
            <input type="text"  class="form-control max" id="bank_address" placeholder="Enter Bank Address" name="bank_address"   autocomplete="off" value="{{ old('bank_address') }}" autofocus>
            @error('bank_address')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card-footer justify-content-between">
        <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Save" id="data_save" >Save</button>
      </div>
    </form>
    @endif
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
      <thead class="bg-dark text-center">
        <tr>
          <th width="10">SN</th>
          <th class="100">Bank Name</th>
          <th>Account No</th>
          <th width="text-left">Bank Address</th>
          {{-- <th>Status</th> --}}
          <th width="100">Action</th>
        </tr>
      </thead>
      @foreach($staffhasbanks as $key=>$staffhasbank)
      <tr class="text-center" data-toggle="tooltip" data-placement="top" title="{{ $staffhasbank->is_active == '1' ? 'This data is published':' This data is not published'}}" style="background-color: {{$staffhasbank->is_active == '1' ? '#cde0ba' : '#eccdcd'}}">
        <td>{{$key+1}}</td>
        <td>{{$staffhasbank->bank_name}}</td>
        <td>{{$staffhasbank->account_no}}</td>
        <td>{{$staffhasbank->bank_address}}</td>
        {{-- <td>
          <a href="" data-toggle="tooltip" data-placement="top" title="{{ $staffhasbank->is_active == '1' ? 'Click to deactivate' : 'Click to activate' }}">
            <i class="fa {{ $staffhasbank->is_active == '1' ? 'fa-check check-css':'fa-times cross-css'}}"></i>
          </a>
        </td> --}}
        <td class="text-center">
          <form action="{{ route('admin.staffhasbank.destroy',$staffhasbank->id) }}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
            @csrf
            @method('DELETE')
            <button class="btn btn-xs btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
          </form>
        </td>
      </tr>
      @endforeach              
    </table>
  </div>
</section>
@endsection
