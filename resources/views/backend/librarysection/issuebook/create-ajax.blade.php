<label class="control-label">Total Record Found: <span class="badge badge-info">{{$issuebooks_count}}</span></label>
<table class="table table-bordered table-hover position-relative w-100 m-0">
  <thead class="bg-dark">
    <tr class="text-center">
      <th width="10">SN</th>
      <th class="text-left">Book</th>
      <th class="text-left">Issue Date</th>
    </tr>
  </thead> 
  <tbody>
    @foreach($issuebooks as $index=>$issuebook)
    <tr>
      <td>{{$index+1}}</td>
      <td>{{$issuebook->getBook->name}} <span class="badge badge-info">{{$issuebook->getBook->book_no}}</span></td>
      <td><span class="badge badge-warning">{{$issuebook->issue_date}}</span></td>
    </tr>
    @endforeach
  </tbody>             
</table>