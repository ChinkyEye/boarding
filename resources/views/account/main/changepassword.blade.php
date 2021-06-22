@extends('teacher.main.app')
@section('content')
<section class="content-header">
</section>
<section class="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="POST" action="{{ route('teacher.change.password') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="new-password" class="control-label">Current Password</label>

                            <input id="current-password" type="password" class="form-control {{$errors->has('current_password') ? 'is-invalid' : ''}}" name="current_password">

                            @if ($errors->has('current_password'))
                            <span class="text-danger">
                                <strong>{{ $errors->first('current_password') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="new-password" class="control-label">New Password</label>

                            <input id="new-password" type="password" class="form-control {{$errors->has('current_password') ? 'is-invalid' : ''}}" name="new_password">

                            @if ($errors->has('new_password'))
                            <span class="text-danger">
                                <strong>{{ $errors->first('new_password') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="new-password-confirm" class="control-label">Confirm New Password</label>

                            <input id="new-password-confirm" type="password" class="form-control {{$errors->has('new_password_confirmation') ? 'is-invalid' : ''}}" name="new_password_confirmation">
                            @if ($errors->has('new_password_confirmation'))
                            <span class="text-danger">
                                <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection