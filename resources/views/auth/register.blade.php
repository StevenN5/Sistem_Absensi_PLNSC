@extends('layouts.master-blank')

@section('content')
@section('css')
    <style>
        .wrapper-page {
            width: 100%;
            max-width: 100%;
            padding: 24px 32px;
        }
        .register-wide {
            width: 100%;
            max-width: 100%;
        }
        .register-wide .account-card {
            border-radius: 18px;
        }
        .register-wide .form-control {
            height: calc(1.5em + 1.1rem + 2px);
            padding: 0.55rem 0.9rem;
        }
        .register-wide .form-row {
            margin-left: -10px;
            margin-right: -10px;
        }
        .register-wide .form-row > [class*="col-"] {
            padding-left: 10px;
            padding-right: 10px;
        }
        @media (max-width: 991px) {
            .wrapper-page {
                padding: 16px;
            }
        }
    </style>
@endsection
    <div class="wrapper-page">
        <div class="card overflow-hidden account-card register-wide">
            <div class="bg-primary p-4 text-white text-center position-relative">
                <h4 class="font-20 m-b-5">{{ __('global.create_account') }}</h4>
                <p class="text-white-50 mb-4">{{ __('global.employees') }}</p>
                <a href="{{ route('welcome') }}" class="logo logo-admin">
                    <h1>A</h1>
                </a>
            </div>
            <div class="account-card-content">
                <form class="form-horizontal m-t-30" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name" class="col-form-label">{{ __('global.name') }}</label>
                            <input id="name" type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autocomplete="name"
                                placeholder="{{ __('global.placeholder_full_name') }}" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phone_number" class="col-form-label">{{ __('global.phone_number') }}</label>
                            <input id="phone_number" type="text"
                                class="form-control @error('phone_number') is-invalid @enderror"
                                name="phone_number" value="{{ old('phone_number') }}" required autocomplete="tel"
                                placeholder="{{ __('global.placeholder_phone') }}">

                            @error('phone_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="address" class="col-form-label">{{ __('global.address') }}</label>
                            <input id="address" type="text"
                                class="form-control @error('address') is-invalid @enderror"
                                name="address" value="{{ old('address') }}" required autocomplete="street-address"
                                placeholder="{{ __('global.placeholder_address') }}">

                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="birth_date" class="col-form-label">{{ __('global.birth_date') }}</label>
                            <input id="birth_date" type="date"
                                class="form-control @error('birth_date') is-invalid @enderror"
                                name="birth_date" value="{{ old('birth_date') }}" required autocomplete="bday">

                            @error('birth_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="institution" class="col-form-label">{{ __('global.institution') }}</label>
                            <input id="institution" type="text"
                                class="form-control @error('institution') is-invalid @enderror"
                                name="institution" value="{{ old('institution') }}" required autocomplete="organization"
                                placeholder="{{ __('global.placeholder_institution') }}">

                            @error('institution')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="position" class="col-form-label">{{ __('global.position') }}</label>
                            <input id="position" type="text"
                                class="form-control @error('position') is-invalid @enderror"
                                name="position" value="{{ old('position') }}" required autocomplete="position"
                                placeholder="{{ __('global.placeholder_position') }}">

                            @error('position')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="major" class="col-form-label">{{ __('global.major') }}</label>
                            <input id="major" type="text"
                                class="form-control @error('major') is-invalid @enderror"
                                name="major" value="{{ old('major') }}" required autocomplete="major"
                                placeholder="{{ __('global.placeholder_major') }}">

                            @error('major')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="email" class="col-form-label">{{ __('global.email') }}</label>
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email"
                                placeholder="{{ __('global.placeholder_email') }}">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="password" class="col-form-label">{{ __('global.login_password') }}</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label for="password-confirm" class="col-form-label">{{ __('global.confirm_password') }}</label>
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="toggle-password">
                            <label class="form-check-label" for="toggle-password">{{ __('global.show_password') }}</label>
                        </div>
                    </div>

                    <div class="form-group row m-t-20">
                        <div class="col-sm-6">
                            <a href="{{ route('login') }}" class="text-muted">{{ __('global.already_have_account') }}</a>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button class="btn btn-primary w-md waves-effect waves-light" type="submit">{{ __('global.register') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    (function () {
        var toggle = document.getElementById('toggle-password');
        var password = document.getElementById('password');
        var confirm = document.getElementById('password-confirm');

        if (!toggle || !password || !confirm) {
            return;
        }

        toggle.addEventListener('change', function () {
            var type = toggle.checked ? 'text' : 'password';
            password.type = type;
            confirm.type = type;
        });
    })();
</script>
@endsection
