<button type="button" class="btn btn-info btn-xs hidden-print float-right" onclick="PrintDiv('printDiv')" >PRINT<i class="fa fa-print"></i></button>
<div id="printDiv">
  <table class="table table-bordered table-hover position-relative m-0">
    <thead class="bg-dark text-center th-sticky-top">
      <tr>
        <th width="10">SN</th>
        <th class="text-left">Student</th>
        <th>Invoice No</th>
        <th>Date/Time</th>
        <th width="150">Discount</th>
        <th width="150">Fine</th>
        <th width="150">Fee</th>
        <th width="150">Total</th>
      </tr>
    </thead>              
    @foreach ($fee_list as $key=>$element)
      <tr class="text-center">
        <td>{{$key+1}}</td>
        <td class="text-left">{{$element->getStudentinfo->name}} {{$element->getStudentinfo->middle_name}} {{$element->getStudentinfo->last_name}}</td>
        <td><a href="{{route('admin.bill.print',['bill_id' => $element->invoice_id])}}" target="_blank">{{ $element->invoice_id }}</a></td>
        <td>{{$element->bill_date}} {{$element->bill_time}}</td>
        <td>Rs. {{$element->discount}}</td>
        <td>Rs. {{$element->fine}}</td>
        <td>Rs. {{$element->total}}</td>
        <td>Rs. {{$element->nettotal}}</td>
      </tr>
    @endforeach
    <tfoot>
      <tr>
        <td colspan="4" class="text-right"><b>Total</b></td>
        <td class="text-center">Rs. {{$total_discount}}</td>
        <td class="text-center">Rs. {{$total_fine}}</td>
        <td class="text-center">Rs. {{$total_fee}}</td>
        <td class="text-center">Rs. {{$net_total_fee}}</td>
      </tr>
    </tfoot>
  </table>
</div>
<div class="card-footer">
  {!! $fee_list->appends(request()->input())->links() !!}
</div>