@extends('account.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 8, strpos(str_replace('account.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
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
    @foreach($teacherincomes as $income)
    <form role="form" method="POST" action="{{ route('account.salary.update',$income->id)}}">
      <div class="card-body">
        @method('PATCH')
        @csrf
        <div class="row">
          <div class="form-group col-sm-6">
            <label for="amount">Amount:<span class="text-danger">*</span></label>
            <input type="text"  class="form-control max" id="amount" placeholder="Enter Amount" name="amount"   autocomplete="off" value="{{ $income->amount }}" autofocus>
            @error('amount')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          
          <div class="form-group col-sm-6">
            <label for="grade">Grade:</label>
            <input type="text"  class="form-control max" id="grade" placeholder="Enter Grade" name="grade"  autocomplete="off" value="{{ $income->grade }}">
          </div>
          <div class="form-group col-sm-6">
            <label for="mahangi_vatta">Mahangi Vatta:</label>
            <input type="text"  class="form-control max" id="mahangi_vatta" placeholder="Enter Mahangi Vatta" name="mahangi_vatta"  autocomplete="off" value="{{ $income->mahangi_vatta}}">
          </div>
          <div class="form-group col-sm-6">
            <label for="durgam_vatta">Durgam Vatta:</label>
            <input type="text"  class="form-control max" id="durgam_vatta" placeholder="Enter Durgam Vatta" name="durgam_vatta"  autocomplete="off" value="{{ $income->durgam_vatta }}">
          </div>
          
          <div class="form-group col-sm-6">
            <label for="citizen_investment_deduction">Ctizen Investment Deduction:<span class="text-danger">*</span></label>
            <input type="text"  class="form-control max" id="citizen_investment_deduction" placeholder="Enter citizen investment deduction number" name="citizen_investment_deduction" autocomplete="off" value="{{ $income->citizen_investment_deduction }}">
          </div>
          
          <div class="form-group col-sm-6">
            <label for="loan_deduction">Loan Deduction:</label>
            <input type="text"  class="form-control max" id="loan_deduction" placeholder="Enter Address" name="loan deduction"  autocomplete="off" value="{{ $income->loan_deduction }}">
          </div>
          <div class="form-group col-sm-6">
            <label for="cloth_amount">Cloth Amount:</label>
            <input type="text"  class="form-control max" id="cloth_amount" placeholder="Enter Cloth Amount" name="cloth_amount"  autocomplete="off" value="{{ $income->cloth_amount }}">
          </div>
          <div class="form-group col-sm-6">
            <label for="cloth_amount">Permanent Allowance:</label>
            <input type="text"  class="form-control max" id="permanent_allowance" placeholder="Enter Cloth Amount" name="permanent_allowance"  autocomplete="off" value="{{ $income->permanent_allowance }}">
          </div>
          <fieldset class="border container-fluid col-md-12 p-2 mb-2">
            <legend  class="w-auto"><small class="mx-1 text-info">Pradyanadhyapak Vattā is given only for Principal and Chadparva Kharcha is given during festival.Leave it if not needed</small></legend>
            <div class="row">
              <div class="form-group col-sm-6">
                <label for="cloth_amount">Pradyanadhyapak Vattā:</label>
                <input type="text"  class="form-control max" id="pradyanadhyapak_bhattā" placeholder="Enter Amount" name="pradyanadhyapak_bhattā"  autocomplete="off" value="{{ $income->pradyanadhyapak_bhattā}}">
              </div>
              <div class="form-group col-sm-6">
                <label for="cloth_amount">Chadparva Kharcha:</label>
                <input type="text"  class="form-control max" id="chadparva_kharcha" placeholder="Enter Amount" name="chadparva_kharcha"  autocomplete="off" value="{{ $income->chadparva_kharcha}}">
              </div>
            </div>

          </fieldset>
          <div class="form-group col-sm-6">
            <label for="remark">Remarks:</label>
            <input type="text"  class="form-control max" id="remark" placeholder="Enter Remark" name="remark"  autocomplete="off" value="{{ $income->remark }}">
          </div>
        </div>
        
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
    @endforeach
  </div>
</section>
@endsection