<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <title>
        L21 | Register
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('css/soft-ui-dashboard.css') }}?v=1.0.7" rel="stylesheet" />
</head>

<body class="">
    <main class="main-content  mt-0">
        <section class=" mb-5">
            <div class="page-header align-items-start pt-5 pb-11 m-1 border-radius-lg">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 text-center mx-auto">

                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row mt-lg-n10 mt-md-n11 mt-n10">
                    <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                        <div class="card z-index-0">

                            <div class="card-header text-center pt-2 bg-dark-login">

                                <div class="logo d-flex justify-content-center">
                                    <a href=""><img src="{{ asset('img/l21-logo-1.png') }}"> </a>
                                </div>
                                {{-- <h1 class=" font-weight-bolder text-info text-gradient text-center">Daftar</h1> --}}

                                {{-- <h5 class="text-light font-weight-bolder">Register Field</h5> --}}
                            </div>
                            <div class="row px-xl-5 px-sm-4 px-3 ">
                                ` </div>
                            <div class="card-body bg-dark-login">
                                <form action="/trex1diath/register" method="post" role="form text-left">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="name" class="text-white">Name</label>
                                        <input type="text" name="name"
                                            class="form-control rounded-top @error('name') is-invalid @enderror"
                                            id="name" placeholder="name" required autofocus
                                            value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="text-white">Username</label>
                                        <input type="text" name="username"
                                            class="form-control  @error('username') is-invalid @enderror" id="username"
                                            placeholder="username" required value="{{ old('username') }}">
                                        @error('username')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="text-white">Password</label>
                                        <input type="password" name="password"
                                            class="form-control rounded-bottom  @error('password') is-invalid @enderror"
                                            id="password" placeholder="Password" required>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="divisi" class="text-white">Divisi</label>
                                        <select class="form-select @error('divisi') is-invalid @enderror" name="divisi"
                                            id="divisi" required>
                                            <!-- <option value = "100">Pilih BO</option> -->
                                            <option value="" selected>Pilih Bo</option>
                                            @foreach ($databo as $item)
                                                <option value="{{ $item->nama }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('divisi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-primary w-100 mt-4 mb-0">Sign
                                            Up</button>
                                    </div>
                                    <p class="text-sm mt-3 mb-0">Already have an account? <a href="/RTP/tr3xd1ath/login"
                                            class="text-light font-weight-bolder">Sign in</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
        <footer class="footer py-0">
            <div class="container">

                <div class="row">
                    <div class="col-8 mx-auto text-center mt-1">
                        <p class="mb-0 text-secondary">
                            Copyright ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script> Soft by Creative Tim.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    </main>
    <!--   Core JS Files   -->
    <script src="{{ asset('js/core/popper.min.js') }}"></script>
    <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('js/soft-ui-dashboard.min.js') }}?v=1.0.7"></script>
</body>

</html>
