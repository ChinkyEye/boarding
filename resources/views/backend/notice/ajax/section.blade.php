@if($datas)
	<label for="shift">Section</label>
	@foreach($datas as $key=>$data_list)
	@foreach($data_list as $section_list)
	<div class="form-check">
		<input class="form-check-input section_filter" type="checkbox" name="section_id[]" id="section_{{$key}}{{$section_list->id}}" value="{{$section_list->id}}">
		<label class="form-check-label" for="section_{{$key}}{{$section_list->id}}">
			{{$section_list->name}}
		</label>
	</div>
	@endforeach
	@endforeach
@endif
