@extends('account.main.app')
@push('style')
@endpush
@section('content')
<?php $page = substr((Route::currentRouteName()), 8, strpos(str_replace('account.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }}</h1>
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
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-4">
        <div class="card card-primary card-outline sticky-top">
          <div class="card-body box-profilex">
            <h3 class="profile-username font-weight-bold" data-toggle="tooltip" data-placement="top" title="
            {{ $shift }}, {{ $class }}, {{ $section }}, {{ $student_info->roll_no }}
            "><i class="fas fa-info-circle"></i> {{ $user_info->name }} {{ $user_info->middle_name }} {{ $user_info->last_name }} </h3>
            <select class="form-control" id="item_id">
              <option></option>
              @foreach ($topics as $key => $data)
              <option value="{{ $data->id }}" {{ old('topic_id') == $data->id ? 'selected' : ''}}> 
                {{$data->topic}} ({{$data->fee}})
              </option>
              @endforeach    
            </select>
            <button class="add_item mt-4 btn btn-primary btn-block">Add more</button>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card card-info">
          <form role="form" method="POST" action="{{route('account.bill.store')}}" class="validate" id="validate">
            <div class="card-body">
              @csrf
              <input type="hidden" name="student_id" value="{{ $student_id }}">
              <div class="row">
               <table class="table table-bordered table-hover position-relative m-0" id="myTable">
                 <thead class="bg-dark th-sticky-top">
                   <tr>
                     <th class="w-75">Item</th>
                     <th colspan="2" class="text-right">Amount</th>
                   </tr>
                 </thead>
                <tbody id="bill_ppend_list"></tbody>
                <tfoot id="bill_calculate_list"></tfoot>
               </table>
              </div>
            </div>
            <div class="card-footer justify-content-between">
              <div class="float-right">
                <button class="btn btn-warning d-none" id="calculate">Calculate</button>
                <button type="submit" class="btn btn-info text-capitalize d-none" id="submit" data-toggle="tooltip" data-placement="top" title="Save Bill">Save Bill</button>
              </div>
            </div>
          </form>
        </div>
        
      </div>
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
   
    $('#item_id').select2({
      placeholder: "Select Your Topic",
    });
  });
</script>
<script>
  function percentage(){
    Pace.start();
    // debugger;
    var  discount = document.getElementById('discount').value;
    var  total_fee = document.getElementById('total_fee').value;
    var percentage = parseInt(discount);
    var nettotal = total_fee - percentage;
    debugger;
    Pace.stop();
    $('#totalwithfine').val(nettotal);
  }
  function totalfine(){
    Pace.start();
    // debugger;
    var  fine = document.getElementById('fine').value;
    var  discount = document.getElementById('discount').value;
    var  total_fee = document.getElementById('total_fee').value;
    var percentage = parseInt(discount);
    var nettotal = total_fee - percentage;
    var totalwithfine = parseInt(nettotal) + parseInt(fine);
    Pace.stop();
    $('#totalwithfine').val(totalwithfine);
  }
</script>
<script type="text/javascript">
  $('.add_item').click(function(event){
    var item_id = $("#item_id").val();
    // debugger;
    var  token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"html",
      url:"{{route('account.getBillFeeList')}}",
      data:{
        _token:token,
        item_id: item_id
      },
      success: function(response){
        $('#bill_ppend_list').append(response);
        $('table').on('click','#cross',function(e){
          e.preventDefault();
          $(this).closest('tr').remove();
        });
        $("#submit").addClass('d-none');
        $("#calculate").removeClass('d-none');
        $('#fee,#discount-tr,#fine-tr,#net-total-tr').remove();
      },
      error:function(event){
        alert('Error');
        return false;
      }
    })
  })
</script>
<script type="text/javascript">
  $('#calculate').click(function(event){
    debugger;
    event.preventDefault();
        var data_id = new Array();
        $('input[name="topic_id[]"]').each(function () {
          data_id.push(this.value);
        });
      var  token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
        type:"POST",
        dataType:"html",
        url:"{{route('account.getBillCalculateList')}}",
        data:{
          _token:token,
          topic_id: data_id
        },
        success: function(response){
          // $('#bill_calculate_list').html('');
          $("#submit").removeClass('d-none');
          $('#total_fee').val('');
          $('#bill_calculate_list').append(response);
          $('#discount').focus();
          $("#calculate").addClass('d-none');
        },
        error:function(event){
          alert('Error');
          return false;
        }
      });
  });
</script>

<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$().ready(function() {
  $("#validate").validate({
    rules: {
      name: {
        required: true,
        minlength: 2
      }
    },
    messages: {
      name: {
        required: "Please enter a nationality",
        minlength: "Nationality must consist of at least 2 characters"
      }
    },
    highlight: function(element) {
     $(element).css('background', '#ffdddd');
     $(element).css('border-color', 'red');
    },
    unhighlight: function(element) {
     $(element).css('background', '#ffffff');
     $(element).css('border-color', 'green');
    }
  });
});
</script>
@endpush