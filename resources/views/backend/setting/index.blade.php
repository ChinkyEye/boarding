@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/setting.main.css">
@endpush
@section('content')
<section class="content-header">
  
</section>
<section class="content">
  <div class="p-4">
    <div class="row container d-flex justify-content-center">
      <div class="col-md-12">
        <div class="card user-card-full">
          <div class="row ml-0 mr-0">
            <div class="col-sm-4 bg-c-lite-green user-profile">
              <div class="card-block text-center text-white">
                <div class="mb-3"> 
                  <img src="{{URL::to('/')}}/images/main/{{$settings->slug}}/{{$settings->image}}" class="img-fluid img-style rounded-circle" alt="{{$settings->name}}"> 
                </div>
                <h5 class="font-weight-bolder">{{$settings->school_name}}</h5>
                <p>{{$settings->address}}</p> 
                {{-- <a href="{{ route('admin.setting.edit',$settings->id) }}" class="text-light" data-toggle="tooltip" data-placement="top" title="Update"><i class="fas fa-edit"></i></a> --}}
                <a href="{{ route('admin.setting.edit',$settings->id) }}" class="continue-application text-light" data-toggle="tooltip" data-placement="top" title="Update">
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
                  {{-- Edit --}}
                </a>
              </div>
            </div>
            <div class="col-sm-8 my-auto">
              <div class="card-block">
                <h5 class="mb-3 pb-2 border-bottom font-weight-bolder">School Info</h5>
                <div class="row">
                  <div class="col-sm-6">
                    <p class="mb-2 font-weight-bolder">Email</p>
                    <h6 class="text-muted font-weight-bold">{{$settings->email}}</h6>
                  </div>
                  <div class="col-sm-6">
                    <p class="mb-2 font-weight-bolder">Phone</p>
                    <h6 class="text-muted font-weight-bold">{{$settings->phone_no}}</h6>
                  </div>
                </div>
                <h5 class="mb-3 mt-3 pb-2 border-bottom font-weight-bolder">Principle Info</h5>
                <div class="row">
                  <div class="col-sm-6">
                    <p class="mb-2 font-weight-bolder">Name</p>
                    <h6 class="text-muted font-weight-bold">{{$settings->principal_name}}</h6>
                  </div>
                  <div class="col-sm-6">
                    <p class="mb-2 font-weight-bolder">Email</p>
                    <h6 class="text-muted font-weight-bold">{{$settings->getAdminInfo->email}}</h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@push('javascript')

@endpush