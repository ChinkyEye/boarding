<tr class="text-right" id="fee">
	<td>
		Total
	</td>
	<td>
		<input class="w-100 text-right border-0 bg-transparent focus-0" type="text" name="total_fee" value="{{$total_fee}}" id="total_fee" readonly>
	</td>
</tr>
<tr class="text-right" id="discount-tr">
	<td>
		Discount(Rs)
	</td>
	<td>
		<input class="w-100 text-right main-border-text-input focus-0" type="text" name="discount" onkeyup="percentage()" id="discount" value="0" autofocus="true" autocomplete="off">
	</td>
</tr>
<tr class="text-right" id="fine-tr">
	<td>
		Fine
	</td>
	<td>
		<input class="w-100 text-right main-border-text-input focus-0" type="text" name="fine" onkeyup="totalfine()" id="fine" value="0" autocomplete="off">
	</td>
</tr>
<tr class="text-right" id="net-total-tr">
	<td>
		Net Total
	</td>
	<td>
		<input class="w-100 text-right border-0 bg-transparent focus-0" type="text" id="totalwithfine" value="{{$total_fee}}" readonly>
		<input class="w-100 text-right border-0 bg-transparent" type="hidden" name="nettotal" id="nettotal" value="{{$total_fee}}">
	</td>
</tr>