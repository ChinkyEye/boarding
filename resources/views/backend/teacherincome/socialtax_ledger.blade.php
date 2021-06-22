@extends('backend.main.app')
@section('content')
<?php $page = str_replace('admin.','',Route::currentRouteName()); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    <div class="card-header">
      <h2 class="text-center">mkdmek</h2>
      <h2 class="text-center">mkdmek</h2>
      <h2 class="text-center">गोश्रारा भौचर </h2>
    	{{-- <div class="card-tools">
    		<button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="{{request()->url()}}" data-source-selector="#card-refresh-content"><i class="fas fa-sync-alt"></i></button>
    		<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
    		<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
    		<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
    	</div> --}}
       
    <div class="card-body">
      <div class="container mb-4 mt-5 ">
        {{-- <h2 class="text-left">mkdmek</h2> --}}
        {{-- <h2 class="text-left">mkdmek</h2> --}}
        {{-- <h2 class="text-right">mkdmek</h2> --}}
        {{-- <h2 class="text-right">mkdmek</h2> --}}
        <div class="row">
          <div class="col-md-6">
            <p>बजेट उप शीर्षक नं ......</p>
            <p>बिद्यालय कोद नं......</p>
          </div>
          <div class="col-md-6">
            <p class="text-center">मिती :</p>
            <p class="text-center">मुल गो.भौ.नं</p>
            <p class="text-center">बिधुतिय कारोबर नं</p>
          </div>
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr class="text-center">
                    <th width="10" rowspan="2">क्र स </th>
                    <th width="100" rowspan="2">सकेत उप-शीर्षक नम्बर </th>
                    <th width="100" rowspan="2">कृयकलाप कार्यक्रम सकेत नं </th>
                    <th width="100" rowspan="2">करोबार्को  ब्यहोरा </th>
                    <th width="100" rowspan="2">खाता पाना नं</th>
                    <th width="100" colspan="4">स्रोतको</th>
                    <th width="100" rowspan="2">डेबिट  </th>
                    <th width="100" rowspan="2">क्रेडिट</th>
                  </tr>
                  <tr>
                    <th>तह</th>
                    <th>स्रोत व्यहोर्ने संस्था</th>
                    <th>प्रकार</th>
                    <th>भुक्तानी बिधी </th>
                  </tr>
                </thead>              
                <tr class="text-center">
                  <td>१</td>
                  <td>२</td>
                  <td>३</td>
                  <td>४</td>
                  <td>५</td>
                  <td>६</td>
                  <td>७</td>
                  <td>८</td>
                  <td>९</td>
                  <td>१०</td>
                  <td>११</td>
                </tr>
                <tr class="text-center">
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr class="text-center">
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                
                <tr class="text-right">
                  <td colspan="9">जम्मा </td>
                  <td></td>
                  <td></td>
                </tr>
              </table>
              <p class="text-right">जम्मा रकम अक्ष्यारमा </p>
            </div>
            <div class="col-md-12">
              <p><u>करोबार को ब्यहोरा </u></p>
              <input class="effect-none form-control" type="text" placeholder="_">
            </div>
            <hr>
         
            
          </div>
          <div class="col-md-8">
            <p>(भुक्तनी प्रायोजनका लागि)</p>
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr class="text-center">
                    <th width="10">क्र स</th>
                    <th width="100">भुक्तनी पाउने को नाम </th>
                    <th width="100">बैंक खाता नं/ भार्पाइ नं </th>
                    <th width="100">पान नं</th>
                    <th width="100">भुक्तनी रकम  </th>
                  </tr>
                </thead>              
                <tr class="text-center">
                  <td>१</td>
                  <td>२</td>
                  <td>३</td>
                  <td>४</td>
                  <td>५</td>
                </tr>
                <tr class="text-center">
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                
                <tr class="text-right">
                  <td colspan="3">जम्मा </td>
                  <td></td>
                  <td></td>
                </tr>
              </table>
              {{-- <p class="text-right">dev</p> --}}
            </div>
            
          </div>
          <div class="col-md-4">
            <p class="text-center">सङ्लग्ना कागजात सन्ख्या -</p>
            <p class="text-center">तह - स्थानीया </p>
            <p class="text-center">संस्था - काठमाडौं महानगरपालिका </p>
            <p class="text-center">प्रकार - नगद </p>
          </div>
          <div class="col-md-4">
            <p class="text-left">तयार गर्ने</p>
            <p class="text-left">मिती </p>
            <p class="text-left">दर्जा</p>
          </div>
          <div class="col-md-4">
            <p class="text-left">पेश गर्ने</p>
            <p class="text-left">मिती</p>
            <p class="text-left">दर्जा</p>
          </div>
          <div class="col-md-4">
            <p class="text-left">स्विकृत गर्ने</p>
            <p class="text-left">मिती</p>
            <p class="text-left">दर्जा</p>
          </div>

        </div>
        <hr>
        <div class="row">
          <div class="col-sm-12">
            <form>
              <div class="form-group row upper-case">
                <label class="col-sm-4"><h2>श्री </h2></label>
                <div class="col-sm-4">
                  {{-- <input class="effect-none" type="text" value="{{$voucher->school->schoolName}}"> --}}
                </div>
                 <label class="col-sm-4"><h3>बिद्यालय</h3></label>
              </div>
            </form>
            <h2>मोरङ्</h2>
          </div>
          <div class="col-12">
            <div class="row">
              <div class="col-md-8">
                <form>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">पत्र संख्या :</label>
                    <div class="col-sm-10">
                      <input class="effect-none" type="text" placeholder="_">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">चलानी नम्बर :</label>
                    <div class="col-sm-10">
                      <input class="effect-none" type="text" placeholder="_">
                    </div>
                  </div>
                </form>
              </div>
              <div class="col-md-4">
                <div class="row">
                  <div class="form-group row miti">
                    <label class="col  col-form-label">मिति:</label>
                    <div class="col">
                     <input class="effect-none" type="text" placeholder="0000/00/00">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 Max-sub">
            <h2>बिषय: सामाजिक सुरक्षा कर दाखिलाको भौचर पठाएको बारे |</h2>
            <h3>श्री आन्तरिक राजस्व कार्यालय</h3>
            <h3>बिराटनगर</h3>
            <div class="row">
              <div class="col-sm-12 mt-5">
                <form>
                  <div class="form-group row upper-case">
                    <label class="col-sm-4">यस बिद्यालयमा कार्यरत निमन शिक्षक्हरुको</label>
                    <div class="col-sm-2">
                      <input class="effect-none" type="text" placeholder="२०७६/00/00">
                    </div>
                    <label class="col-sm-6">तलब भत्ता भुक्तानी हुदा कट्टी भएको सामाजिक सुरक्षा कर को रकम मिति </label>
                  </div>
                  <div class="form-group row upper-case">
                    <div class="col-sm-2">
                      <input class="effect-none" type="text" placeholder="२०७६/00/00">
                    </div>
                    <label class="col-sm-1">भौ.नं.</label>
                    <div class="col-sm-1">
                      <input class="effect-none" type="text" placeholder="_">
                    </div>
                    <label class="col-sm-1">बाट</label>
                    <div class="col-sm-3">
                      <input class="effect-none" type="text" placeholder="_">
                    </div>
                    <label class="col-sm-1">बैंक</label>
                    <div class="col-sm-3">
                      <input class="effect-none" type="text" placeholder="_">
                    </div>
                  </div>
                  <div class="form-group row upper-case">
                    <label class="col">मा राजस्व खाता नं.क ११२११ मा जम्मा गरेको भौचर थान १ यसै साथ संग्लन गरी पठाईएको ब्यहोरा अनुरोध छ |</label>
                  </div>
                </form>
              </div>
            </div>
          </div>
          
          <div class="col mt-2">
            <div class="row">
              <table class="table table-sm" border="1">
                <thead>
                  <tr>
                    <th rowspan="2">सि न.</th>
                    <th rowspan="2">पद</th>
                    <th rowspan="2">कर्मचारीको नाम</th>
                    <th rowspan="2">स्थाई लेखा नम्बर </th>
                    <th rowspan="2">यो महिना </th>
                    <th rowspan="2">जम्मा दाखिला</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($teachers)>0)
                  @foreach($teachers as $key=>$account)
                  <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$account->getTeacher->designation}}</td>
                    <td>{{$account->getUser->name}} {{$account->getUser->middle_name}} {{$account->getUser->last_name}}</td>
                    <td>{{$account->getTeacher->pan_id}}</td>
                    <td>{{$account->soc_sec_tax}}</td>
                    <td>{{$account->soc_sec_tax}}</td>
                  </tr>
                  @endforeach 
                       @else
                          <tr>
                            <td colspan="8">No social tax details available</td>
                          </tr>                            
                       @endif 
                  <tr>
                      <td></td>
                      <td></td>
                      <td class="text-center">जम्मा</td>
                      <td></td>
                      <td>{{$account->soc_sec_tax}}</td>
                      <td>{{$account->soc_sec_tax}}</td>
                  </tr>
                  
                </tbody>
              </table>
              <div class="col-sm-12 footer">
                <div class="row mt-5">
                  <div class="col-sm-6">
                    <h2>तयार गर्ने:</h2>
                  </div>
                  <div class="col-sm-6">
                    <h2>सदर  गर्ने:</h2>
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