<button type="button" class="btn btn-info btn-xs hidden-print float-right" onclick="PrintDiv('printDiv')" >PRINT<i class="fa fa-print"></i></button>
<div id="printDiv">
  <table class="table table-bordered table-hover position-relative m-0">
    <thead class="bg-dark text-center th-sticky-top">
      <tr>
        <th class="text-left">Student</th>
        <th width="150">Discount</th>
        <th width="150">Fine</th>
        <th width="150">Fee</th>
        <th width="150">Total</th>
      </tr>
    </thead>              
    @foreach ($posts->where('bt',1) as $key=>$element)
      @php
      $mybt2 = $posts->where('user_id',$element->user_id)->where('bt',2)->first();

      //$mybt2= null;
      @endphp
      <tr class="text-center">
        <td class="text-left">{{$element->fname}} {{$element->mname}} {{$element->lname}} ({{$element->user_code}})</td>
        <td>Rs. {{$element->btd}}</td>
        <td>Rs. {{$element->btf}}</td>
        <td>Rs. {{$element->btt}}</td>
        {{-- <td>Rs. mmm{{($mybt2?$mybt2->btnt:0) - $element->btnt}}</td> --}}
        {{-- <td>Rs.{{$bill_totals}} {{$element->btnt - ($mybt2?$mybt2->btnt:0)}}</td> --}}
        <td>Rs.{{$element->btnt - ($mybt2?$mybt2->btnt:0)}}</td>
      </tr>
    @endforeach
  </table>
</div>