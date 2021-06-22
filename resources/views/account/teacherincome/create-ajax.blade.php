{{-- <label class="control-label">Total Record Found: <span class="badge badge-info">{{$try}}</span></label>
<button type="button" class="btn btn-info btn-xs hidden-print float-right" onclick="PrintDiv('printDiv')" >PRINT<i class="fa fa-print"></i></button>
 --}}
<div id="printDiv">  
  <form role="form" method="POST" action="{{route('account.salary.store')}}" class="validate" id="validate">
    <div class="card-body">
      @csrf
      <div class="row">
        <div class="form-group col-md">
          <label for="shift_id" class="control-label">Name</label>
          <span> : {{$find_teacher->getTeacherUser->name}} {{$find_teacher->getTeacherUser->middle_name}} {{$find_teacher->getTeacherUser->last_name}}</span>
        </div>
        <div class="form-group col-md">
          <label for="teacher_id" class="control-label">Phone Number</label>
          <span> : {{$find_teacher->phone}}</span>
        </div>
        <div class="form-group col-md">
          <label for="teacher_id" class="control-label">Address</label>
          <span> : {{$find_teacher->address}}</span>
        </div>
        <div class="form-group col-md">
          <label for="class_id" class="control-label">Government Id</label>
          <span>: {{$find_teacher->government_id}}</span>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md">
          <label for="section_id" class="control-label">Insurance Id</label>
          <span>: {{$find_teacher->insurance_id}}</span>
        </div>
         <div class="form-group col-md">
          <label for="section_id" class="control-label">PAN Number</label>
          <span>: {{$find_teacher->pan_id}}</span>
        </div>
        <div class="form-group col-md">
          <label for="section_id" class="control-label">Citizenship Number</label>
          <span>: {{$find_teacher->cinvestment_id}}</span>
        </div>
        <div class="form-group col-md">
          <label for="section_id" class="control-label">Providence Fund Id</label>
          <span>: {{$find_teacher->pfund_id}}</span>
        </div>
      </div>
      <h1>Salary Scale</h1>
      <input type="hidden"  id="date" name="date"  value="{{$request->date}}">
      <input type="hidden"  id="month"  name="month"   autocomplete="off" value="{{ $request->month }}">
      <input type="hidden"  id="teacher_id"  name="teacher_id"   autocomplete="off" value="{{$find_teacher->id}}">
      <input type="hidden"  id="user_id"  name="user_id"   autocomplete="off" value="{{ $find_teacher->user_id }}">
      @if(count($teacherincomes) > 0)
      @foreach($teacherincomes as $income)
      <div class="row">
        <div class="form-group col-sm-6">
          <label for="amount">Amount:<span class="text-danger">*</span></label>
          <input type="text"  class="form-control max" id="amount" placeholder="Enter Amount" name="amount"   autocomplete="off" value="{{ $income->amount}}" autofocus>
        </div>
        
        <div class="form-group col-sm-6">
          <label for="grade">Grade:</label>
          <input type="text"  class="form-control max" id="grade" placeholder="Enter Grade" name="grade"  autocomplete="off" value="{{ $income->grade }}">
        </div>
        <div class="form-group col-sm-6">
          <label for="mahangi_vatta">Mahangi Vatta:</label>
          <input type="text"  class="form-control max" id="mahangi_vatta" placeholder="Enter Mahangi Vatta" name="mahangi_vatta"  autocomplete="off" value="{{ $income->mahangi_vatta }}">
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
          <input type="text"  class="form-control max" id="permanent_allowance" placeholder="Enter Amount" name="permanent_allowance"  autocomplete="off" value="{{$income->permanent_allowance}}">
        </div>
        <div class="form-group col-sm-12">
          <label for="remark">Remarks:</label>
          <textarea class="form-control max" id="remark" name="remark" rows="3" placeholder="Enter Remark.....">{{$income->remark}}</textarea>
        </div>
        {{-- <div class="form-group col-sm-6">
          <label for="remark">Remarks:</label>
          <input type="text"  class="form-control max" id="remark" placeholder="Enter Remark" name="remark"  autocomplete="off" value="{{ $income->remark }}">
        </div> --}}
      </div>
      @endforeach

      @else
      <div class="row">
        <div class="form-group col-sm-6">
          <label for="amount">Amount:<span class="text-danger">*</span></label>
          <input type="text"  class="form-control max" id="amount" placeholder="Enter Amount" name="amount"   autocomplete="off" value="{{ old('amount') }}" autofocus>
          @error('amount')
          <span class="text-danger font-italic" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        
        <div class="form-group col-sm-6">
          <label for="grade">Grade:</label>
          <input type="text"  class="form-control max" id="grade" placeholder="Enter Grade" name="grade"  autocomplete="off" value="{{ old('grade') }}">
        </div>
        <div class="form-group col-sm-6">
          <label for="mahangi_vatta">Mahangi Vatta:</label>
          <input type="text"  class="form-control max" id="mahangi_vatta" placeholder="Enter Mahangi Vatta" name="mahangi_vatta"  autocomplete="off" value="{{ old('mahangi_vatta') }}">
        </div>
        <div class="form-group col-sm-6">
          <label for="durgam_vatta">Durgam Vatta:</label>
          <input type="text"  class="form-control max" id="durgam_vatta" placeholder="Enter Durgam Vatta" name="durgam_vatta"  autocomplete="off" value="{{ old('durgam_vatta') }}">
        </div>
        
        <div class="form-group col-sm-6">
          <label for="citizen_investment_deduction">Ctizen Investment Deduction:</label>
          <input type="text"  class="form-control max" id="citizen_investment_deduction" placeholder="Enter citizen investment deduction number" name="citizen_investment_deduction" autocomplete="off" value="{{ old('citizen_investment_deduction') }}">
        </div>
        
        <div class="form-group col-sm-6">
          <label for="loan_deduction">Loan Deduction:</label>
          <input type="text"  class="form-control max" id="loan_deduction" placeholder="Enter Loan Deduction" name="loan deduction"  autocomplete="off" value="{{ old('loan_deduction') }}">
        </div>
        <div class="form-group col-sm-6">
          <label for="cloth_amount">Cloth Amount:</label>
          <input type="text"  class="form-control max" id="cloth_amount" placeholder="Enter Cloth Amount" name="cloth_amount"  autocomplete="off" value="{{ old('cloth_amount') }}">
        </div>
        <div class="form-group col-sm-6">
          <label for="cloth_amount">Permanent Allowance:</label>
          <input type="text"  class="form-control max" id="permanent_allowance" placeholder="Enter Amount" name="permanent_allowance"  autocomplete="off" value="{{ old('permanent_allowance') }}">
        </div>

        <fieldset class="border container-fluid col-md-12 p-2 mb-2">
          <legend  class="w-auto"><small class="mx-1 text-info">Pradyanadhyapak Vattā is given only for Principal and Chadparva Kharcha is given during festival.Leave it if not needed</small></legend>
          <div class="row">
            <div class="form-group col-sm-6">
              <label for="cloth_amount">Pradyanadhyapak Vattā:</label>
              <input type="text"  class="form-control max" id="pradyanadhyapak_bhattā" placeholder="Enter Amount" name="pradyanadhyapak_bhattā"  autocomplete="off" value="{{ old('pradyanadhyapak_bhattā') }}">
            </div>
            <div class="form-group col-sm-6">
              <label for="cloth_amount">Chadparva Kharcha:</label>
              <input type="text"  class="form-control max" id="chadparva_kharcha" placeholder="Enter Amount" name="chadparva_kharcha"  autocomplete="off" value="{{ old('chadparva_kharcha') }}">
            </div>
          </div>
        </fieldset>
        <div class="form-group col-sm-12">
          <label for="remark">Remarks:</label>
          <textarea class="form-control max" id="remark" name="remark" rows="3" placeholder="Enter Remark....."></textarea>
          {{-- <input type="text"  class="form-control max" id="remark" placeholder="Enter Remark" name="remark"  autocomplete="off" value="{{ old('remark') }}"> --}}
        </div>
        <button type="submit" class="btn btn-info text-capitalize" data-toggle="tooltip" data-placement="top" title="Save Shift" id="submits">Save</button>
      </div>
      @endif
    </div>
  </form>              
</div>
<script>
 $('#submits').prop('disabled', true);
 $("body").on("keypress","#amount", function(event){
   $('#submits').prop('disabled', false);
 });
</script>