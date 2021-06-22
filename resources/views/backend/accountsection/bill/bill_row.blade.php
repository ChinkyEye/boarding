  <tr id="bill_row">
    <td>
    	<label>{{$topic}}</label>
    	<input type="hidden" name="topic_id[]" value="{{$item_id}}" id="topic_id">
    </td>
    <td class="text-right">
        {{$fee}}
    </td>
    <td class="text-center" width="10">
        <i class="text-danger fas fa-times" id="cross"></i>
    </td>
  </tr>
