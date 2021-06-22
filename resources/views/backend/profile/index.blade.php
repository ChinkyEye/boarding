@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/setting.main.css">
@endpush
@section('content')
<section class="content-header">
  
</section>
@foreach($profiles as $key=>$profile)
<section class="content">
  <div class="p-4">
    <div class="row container d-flex justify-content-center">
      <div class="col-md-12">
        <div class="card user-card-full">
          <div class="row ml-0 mr-0">
            <div class="col-sm-4 bg-c-lite-green user-profile">
              <div class="card-block text-center text-white">
                <div class="mb-3"> 
                  <img src="{{URL::to('/')}}/images/main/{{Auth::user()->getSchool->slug}}/{{Auth::user()->getSchool->image}}" class="img-fluid img-style rounded-circle" alt="{{Auth::user()->getSchool->name}}"> 
                </div>
                <h5 class="font-weight-bolder">{{$profile->name}} {{$profile->middle_name}} {{$profile->last_name}}</h5>
                <p class="m-0">{{$profile->user_type == '1'? 'Principle' : ''}}</p> 
                <p>Batch : 
                  @if(Auth::user()->getBatch)
                    ({{Auth::user()->getBatch->name}})
                    {{-- <button data-toggle="modal" data-target="#modal-default" id="returndate" data-id="abc" ><i class="fas fa-book"></i></button> --}}
                  @else
                    Not Configure
                    <button data-toggle="modal" data-target="#modal-default" id="returndate" data-id="abc" ><i class="fas fa-book"></i></button>
                  @endif</p> 
                {{-- <a href="{{ route('admin.setting.edit',$profile->id) }}" class="text-light" data-toggle="tooltip" data-placement="top" title="Update"><i class="fas fa-edit"></i></a> --}}
                {{-- <a href="{{ route('admin.setting.edit',$profile->id) }}" class="continue-application text-light" data-toggle="tooltip" data-placement="top" title="Update">
                  <div>
                    <div class="pencil"></div>
                    <div class="folder">
                      <div class="top">
                        <svg viewBox="0 0 24 27">
                          <path d="M1,0 L23,0 C23.5522847,-1.01453063e-16 24,0.44771525 24,1 L24,8.17157288 C24,8.70200585 23.7892863,9.21071368 23.4142136,9.58578644 L20.5857864,12.4142136 C20.2107137,12.7892863 20,13.2979941 20,13.8284271 L20,26 C20,26.5522847 19.5522847,27 19,27 L1,27 C0.44771525,27 6.76353751e-17,26.5522847 0,26 L0,1 C-6.76353751e-17,0.44771525 0.44771525,1.01453063e-16 1,0 Z"></path>
                        </svg>
                      </div>
                      <div class="paper"></div>
                    </div>
                  </div>
                </a> --}}
              </div>
            </div>
            <div class="col-sm-8 my-auto">
              <div class="card-block">
                <h5 class="mb-3 mt-3 pb-2 border-bottom font-weight-bolder">Profile Info</h5>
                <div class="row">
                  <div class="col-sm-6">
                    <p class="mb-2 font-weight-bolder">Email</p>
                    <h6 class="text-muted font-weight-bold">{{$profile->email}}</h6>
                  </div>
                  <div class="col-sm-6">
                    <!-- <p class="mb-2 font-weight-bolder">Phone</p>
                    <h6 class="text-muted font-weight-bold">{{$profile->phone_no}}</h6> -->
                  </div>
                  <div class="col-sm-6">
                    <p class="mb-2 font-weight-bolder">Created By</p>
                    <h6 class="text-muted font-weight-bold">{{$profile->getUser->name}}</h6>
                  </div>
                  @if ($profile->created_at_np)
                  <div class="col-sm-6">
                    <p class="mb-2 font-weight-bolder">Created At</p>
                    <h6 class="text-muted font-weight-bold">{{$profile->created_at_np}} <span class="badge badge-info">{{$profile->created_at->diffForHumans()}}</span></h6>
                  </div>
                  @endif
                  @if ($profile->email_verified_at)
                  <div class="col-sm-6">
                    <p class="mb-2 font-weight-bolder">Email Verified on</p>
                    <h6 class="text-muted font-weight-bold">{{$profile->email_verified_at}}</h6>
                  </div>
                  @endif
                  @if ($profile->reset_time)
                  <div class="col-sm-6">
                    <p class="mb-2 font-weight-bolder">Reset Time</p>
                    <h6 class="text-muted font-weight-bold">{{$profile->reset_time}}</h6>
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="modal-default" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog"  role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h4 class="modal-title text-capitalize">Choose Batch</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form role="form" method="POST" action="{{ route('admin.profile.store')}}" class="categoryform" id="signup">
        {{ csrf_field() }}
        <div class="modal-body" >
          <div class="form-group">
            <label for="return_date">Batch</label>
            <select class="form-control max" name="batch_id">
              <option>--Please select--</option>
              @foreach($batches as $batch)
              <option value="{{$batch->id}}">{{$batch->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="submit" class="btn btn-info text-capitalize">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endsection
