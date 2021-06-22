<div class="card-body">
	<label for="shift">
		Shift
	</label>
	{{-- <div class="form-check">
		<input type="checkbox" class="form-check-input" id="check_all" name="shift_id">
		<label class="form-check-label" for="check_all">
			All
		</label>
	</div> --}}
	@foreach($shifts as $key=>$shift)
	<div class="form-check">
		<input class="form-check-input shift_filter" type="checkbox" name="shift_id[]" id="shift{{$key}}" value="{{$shift->id}}">
		<label class="form-check-label" for="shift{{$key}}">
			{{$shift->name}}
		</label>
	</div>
	@endforeach
</div>