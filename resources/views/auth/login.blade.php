@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.Login')
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('assets/plugins/login/css/page-auth.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/plugins/login/css/firefly.css')}}" />
@endsection

@section('body')

    <body>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
        <div class="firefly"></div>
    @endsection

    @section('content')
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="bg-primary-head">
                                <div class="row">
                                    <div class="col-9">
                                        <div class="text-white p-4">
                                            <h5 class="text-white">Welcome Back !</h5>
                                            <p>Sign in to access FAO Tasklists.</p>
                                        </div>
                                    </div>
                                    <div class="col-3 align-self-end pb-3">
                                        <img src="{{ URL::asset('/assets/images/authentication.png') }}" class="img-fluid" width="80%">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-3">
                                <div class="p-2">
                                    {{-- <form class="form-horizontal" method="POST" action="{{ route('login') }}"> --}}
                                    <form class="form-horizontal" method="GET" action="{{ route('connect') }}">
                                        @csrf
                                        {{-- <div class="mb-3">
                                            <label for="username" class="form-label">Email Address</label>
                                            <input name="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" id="username"
                                                placeholder="Enter your email address" autocomplete="email" autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <div
                                                class="input-group auth-pass-inputgroup @error('password') is-invalid @enderror">
                                                <input type="password" name="password"
                                                    class="form-control  @error('password') is-invalid @enderror"
                                                    id="userpassword" value="" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                    aria-label="Password" aria-describedby="password-addon">
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">
                                                Remember me
                                            </label>
                                        </div> --}}

                                        <div class="mt-3 d-grid">
                                            <button class="btn btn-primary btn-block w-100 text-uppercase" type="submit">SIGN IN (SINGLE SIGN-ON)</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <div class="mt-5 text-center">

                            <div>Copyright © <script>document.write(new Date().getFullYear())</script>
                                Personiv | FAO Tasklists v1.0.0
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end account-pages -->

    @endsection
