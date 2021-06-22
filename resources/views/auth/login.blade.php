@extends('auth.app')
@push('style')
    <link href="{{URL::to('/')}}/logins/style.css" rel="stylesheet">
@endpush
@section('content')
<div id="login-box">
    <div class="logo">
        <img src="{{URL::to('/')}}/logins/img/namaste.png" class="img-fluid img-circle mx-auto d-block" style="width:20%;" />
        <h1 class="logo-caption"><span class="tweak">L</span>ogin</h1>
    </div>
    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}"  >
        @csrf
        <div class="controls {{ $errors->has('email') ? ' has-error' : '' }} {{ $errors->has('password') ? ' has-error' : '' }}">
            <div class="text-center">
                @error('email')
                <span class="invalid-feedback d-block m-0" role="alert">
                    <span class="d-block">{{ $message }}</span>
                </span>
                @enderror
                @error('password')
                <span class="invalid-feedback d-block m-0" role="alert">
                    <span class="d-block">{{ $message }}</span>
                </span>
                @enderror
            </div>

            <input id="email" type="email" placeholder="{{ __('E-Mail Address') }}" class="form-control {{$errors->has('email') ? 'is-invalid' : 'border-0'}}" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus  required/>

            <input type="password" placeholder="{{ __('Password') }}" class="form-control {{$errors->has('password') ? 'is-invalid' : 'border-0'}}" name="password" required autocomplete="current-password"/>

            <button type="submit" class="btn btn-default btn-block btn-custom">{{ __('Login') }}</button>
            
            <div class="form-check form-check-inline text-light d-block my-1">
              <input class="form-check-input" type="checkbox"  name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
              <label class="form-check-label" for="remember"> {{ __('Remember Me') }}</label>
            </div>
            @if (Route::has('password.request'))
                <a class="text-decoration-none color-hyper" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
        </div>
    </form>
    
    {{-- @if ($errors->has('email'))
    <span class="help-block">
        <span class="text-light">{{ $errors->first('email') }}</span>
    </span>
    @endif
    @if ($errors->has('password'))
    <span class="help-block">
        <span class="text-light">{{ $errors->first('password') }}</span>
    </span>
    @endif --}}
    <div class="text-center" style="color: white; margin-top: 10%; font-size: x-small; ">
        <small> Develop by<a href="http://techware.com.np/" target="_blank" class="color-hyper"> Techware Pvt. Ltd</a></small>
    </div>
</div>
@endsection
@push('javascript')
    <script src="{{URL::to('/')}}/logins/anime-jquery.min.js"></script>
    <script src="{{URL::to('/')}}/logins/login.js"></script>
    <script>
      $(document).ready(function() {
        $("#navsection").addClass('d-none');
      });
    </script>
@endpush
