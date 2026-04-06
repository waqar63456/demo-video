@extends('admin.include.head')
@section('head_content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Dashmix - Bootstrap 4 Admin Template &amp; UI Framework</title>

        <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!-- Fonts and Dashmix framework -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700">
        <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/dashmix.min.css') }}">


    </head>
    <style>
        .custom_green {
            color: #24A601;
        }

        .custom_btn {
            background-color: #24A601;
            color: white
        }
    </style>

    <body>


        <div class="row no-gutters justify-content-center bg-body-dark ">
            <div class="hero-static col-sm-10 col-md-8 col-xl-6 d-flex align-items-center p-2 px-sm-0">
                <!-- Sign In Block -->
                <div class="block block-rounded block-fx-pop w-100 mb-0 overflow-hidden bg-image"
                    style="background-image: url('assets/media/photos/photo20@2x.jpg');border: 1px solid green">
                    <div class="row no-gutters">
                        <div class="col-md-6 order-md-1 bg-white">
                            <div class="block-content block-content-full px-lg-5 py-md-5 py-lg-6">
                                <!-- Header -->
                                <div class="mb-2 text-center">
                                    <a class="link-fx font-w700 font-size-h1" href="index.html">
                                        <span class="text-dark">Od</span><span class="custom_green">Share</span>
                                    </a>
                                    <p class="text-uppercase font-w700 font-size-sm text-muted">Sign In</p>
                                </div>
                                <form class="js-validation-signin" action="{{ route('login_process') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="email"
                                                class="form-control form-control-alt @error('email') is-invalid @enderror"
                                                id="email" name="email" placeholder="UserEmail"
                                                value="{{ old('email') }}">
                                            @error('email')
                                                <div class="invalid-feedback d-flex align-items-center">
                                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control form-control-alt @error('password') is-invalid @enderror"
                                                id="password" name="password" placeholder="UserPassword">
                                            @error('password')
                                                <div class="invalid-feedback d-flex align-items-center">
                                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn  btn-block custom_btn">
                                            <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Sign In
                                        </button>
                                    </div>
                                </form>
                                <!-- END Sign In Form -->
                            </div>
                        </div>
                        <div class="col-md-6 order-md-0 bg-success-dark-op d-flex align-items-center">
                            <div class="block-content block-content-full px-lg-5 py-md-5 py-lg-6">
                                <div class="media">
                                    <a class="img-link mr-3" href="javascript:void(0)">
                                        <img class="img-avatar img-avatar-thumb" src="assets/media/avatars/avatar13.jpg"
                                            alt="">
                                    </a>
                                    <div class="media-body">
                                        <p class="text-white font-w600 mb-1">
                                            Welcome to the Admin Panel!
                                        </p>
                                        <a class="text-white-75 font-w600" href="javascript:void(0)">Admin, Web
                                            Developer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Sign In Block -->
            </div>
        </div>

    </body>

    </html>
@endsection
