@foreach($teacherincomes as $key => $teacherincome)
<tr>
   <td>{{$key + 1}}</td>
   @if($teacherincome->month == 1)
   <td>Baisakh</td>
   @elseif($teacherincome->month == 2)
   <td>Jestha</td>
   @elseif($teacherincome->month == 3)
   <td>Asar</td>
   @elseif($teacherincome->month == 4)
   <td>Shrawan</td>
   @elseif($teacherincome->month == 5)
   <td>Bhandra</td>
   @elseif($teacherincome->month == 6)
   <td>Ashoj</td>
   @elseif($teacherincome->month == 7)
   <td>Kartik</td>
   @elseif($teacherincome->month == 8)
   <td>Mangsir</td>
   @elseif($teacherincome->month == 9)
   <td>Poush</td>
   @elseif($teacherincome->month == 10)
   <td>Magh</td>
   @elseif($teacherincome->month == 11)
   <td>Falgun</td>
   @elseif($teacherincome->month == 12)
   <td>Chaitra</td>
   @endif
   <td>{{$teacherincome->getTeacherUser->name}} {{$teacherincome->getTeacherUser->middle_name}} {{$teacherincome->getTeacherUser->last_name}}</td>
   <td>{{number_format($teacherincome->amount)}}</td>
   <td>{{number_format($teacherincome->grade)}}</td>
   <td>{{number_format($teacherincome->mahangi_vatta)}}</td>
   <td>{{number_format($teacherincome->durgam_vatta)}}</td>
   <td>{{number_format($teacherincome->citizen_investment_deduction)}}</td>
   <td>{{number_format($teacherincome->loan_deduction)}}</td>
   <td>{{number_format($teacherincome->cloth_amount)}}</td>
   <td>{{$teacherincome->remark}}</td>
   {{-- <td>{{$teacherincome->getuser->name}}</td> --}}
   <td class="text-center">
    <a href="{{ route('admin.laganikosh',$teacherincome->id) }}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" target='_blank' data-placement="top" title="नागरिक लगानी कोष"><i class="fas fa-plus"></i></a>
    <a href="{{ route('admin.pfledger',$teacherincome->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" target='_blank' title="pfledger"><i class="fas fa-print"></i></a>
    <a href="{{ route('admin.talabmaagfarum',$teacherincome->id) }}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" target='_blank' data-placement="top" title="शिक्षक तलब माग फाराम"><i class="fas fa-file"></i></a>
    <a href="{{ route('admin.gosawaravoucher',$teacherincome->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" target='_blank' data-placement="top" title="गोश्रारा भौचर"><i class="fas fa-file-invoice"></i></a>
    <a href="{{ route('admin.gosawaravoucherkharcha',$teacherincome->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" target='_blank' data-placement="top" title="गोश्रारा भौचर - खर्च"><i class="fas fa-file-alt"></i></a>
    <a href="#" class="btn btn-xs btn-outline-primary" data-toggle="tooltip" target='_blank' data-placement="top" title="समाजिक सुरक्षा कोष"><i class="fas fa-file-alt"></i></a>
   </td>
</tr>
@endforeach