<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/img/favicon.png">
    <title>
        L21 | Panel
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('css/soft-ui-dashboard.css') }}?v=1.0.7" rel="stylesheet" />

</head>

<body class="">
    <div class="container position-sticky z-index-sticky top-0 ">
        <div class="row">
            <div class="col-12">
            </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <section>


            <div class="page-header">
                <div class="container">
                    <div class="row mt-5">
                        <div class="col-xl-4 col-lg-4 col-md-6 d-flex flex-column mx-auto ">
                            <div class="card card-plain rounded custom-box-shadow">
                                <div class="card-header pb-0 text-left bg-dark-login">
                                    @if (session()->has('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if (session()->has('loginError'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('loginError') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <div class="logo d-flex justify-content-center">
                                        <a href=""><img src="{{ asset('img/l21-logo-1.png') }}"> </a>
                                    </div>
                                </div>
                                <div class="card-body bg-dark-login rounded-bottom ">

                                    <form role="form" action="/login" method="post">
                                        @csrf
                                        <label for="username" class="text-white">username</label>
                                        <div class="mb-3">
                                            <input type="username" name="username"
                                                class="form-control  @error('username') is-invalid @enderror"
                                                id="username" placeholder="name@example.com" autofocus required
                                                value="{{ old('username') }}">
                                            @error('username')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <label for="password" class="text-white">Password</label>
                                        <div class="mb-3">
                                            <input type="password" name="password" class="form-control" id="password"
                                                placeholder="Password" required>

                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn bg-gradient-primary w-100 mt-4 mb-0">Sign
                                                in</button>
                                        </div>
                                        <div
                                            class="card-footer text-center pt-0 px-lg-2 px-1 bg-dark-login rounded-bottom mt-4">
                                            <p class="mb-0 text-sm mx-auto">
                                                Don't have an account? Chat Admin !

                                            </p>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <footer class="footer py-5 fixed-bottom">
                <div class="container">
                </div>
                <div class="row sticky-bottom">
                    <div class="col-8 mx-auto text-center mt-1">
                        <p class="mb-0 text-secondary">
                            Copyright ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script> Lotto 21 Group Team.
                        </p>
                    </div>
                </div>
                </div>
            </footer>

        </section>
    </main>


    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('js/soft-ui-dashboard.min.js') }}?v=1.0.7"></script>
</body>

</html>
