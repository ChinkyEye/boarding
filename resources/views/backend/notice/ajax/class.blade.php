@if($datas)
	<label for="shift">Classes</label>
	{{-- {{dd($datas)}} --}}
	@foreach($datas as $key=>$data_list)
	@foreach($data_list as $class_list)
	<div class="form-check">
		<input class="form-check-input class_filter" type="checkbox" name="class_id[]" id="class_{{$key}}{{$class_list->id}}" value="{{$class_list->id}}">
		<label class="form-check-label" for="class_{{$key}}{{$class_list->id}}">
			{{$class_list->name}}
		</label>
	</div>
	@endforeach
	@endforeach
@endif
