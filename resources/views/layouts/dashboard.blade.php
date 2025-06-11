<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Peminjaman | @yield('title')</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('modules/fontawesome/css/all.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <style>
        .dropdown-toggle-custom:after {
            display: none !important;
        }

        .text-wrap-overflow {
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
            width: 100%;
            min-width: 200px;
            max-width: 400px;
            max-height: 100px;
            overflow-y: auto;
            box-sizing: border-box;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>

            <!-- Navbar -->
            @include('components.dashboard-navbar')

            <!-- Sidebar -->
            @include('components.dashboard-sidebar')

            <!-- Main Content -->
            @yield('main')

            <!-- Footer -->
            @include('components.dashboard-footer')
        </div>

        @include('components.help-center-modal')
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('modules/jquery.min.js') }}"></script>
    <script src="{{ asset('modules/popper.js') }}"></script>
    <script src="{{ asset('modules/tooltip.js') }}"></script>
    <script src="{{ asset('modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('modules/moment.min.js') }}"></script>
    <script src="{{ asset('js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('modules/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    @stack('scripts')

    <script>
        // Handle logout
        function handleLogout(event) {
            event.preventDefault();

            swal({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda ingin mengakhiri sesi ini?',
                icon: 'warning',
                buttons: {
                    cancel: 'Batal',
                    confirm: {
                        text: 'Ya, keluar!',
                        value: true,
                    }
                }
            }).then((result) => {
                if (result) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        // Handle messages
        document.addEventListener('DOMContentLoaded', function() {
            let title = '';
            let htmlContent = '';
            let icon = '';

            @if (session('success'))
                title = 'Berhasil!';
                htmlContent = `{!! session('success') !!}`;
                icon = 'success';
            @elseif (session('error'))
                title = 'Gagal!';
                htmlContent = `{!! session('error') !!}`;
                icon = 'error';
            @elseif (session('warning'))
                title = 'Peringatan!';
                htmlContent = `{!! session('warning') !!}`;
                icon = 'warning';
            @endif

            if (title && htmlContent && icon) {
                const content = document.createElement('div');
                content.innerHTML = htmlContent;

                swal({
                    title: title,
                    content: content,
                    icon: icon,
                    button: "OK",
                    timer: 5000
                });
            }
        });
    </script>

    @if (Auth::user() && Auth::user()->role === 'admin')
        <script>
            let pollingInterval;

            function updateNotifUI(response) {
                let total = response.count;
                let displayCount = total > 3 ? '3+' : total;

                $('#notif-count').text(displayCount);

                let html = '';
                if (total === 0) {
                    $('.notification-toggle').removeClass('beep');
                    html += `
                    <div class="dropdown-item text-center text-muted">
                        Tidak ada notifikasi.
                    </div>`;
                } else {
                    $('.notification-toggle').addClass('beep');
                    response.data.forEach(function(item) {
                        html += `
                        <a href="{{ url('/parent-data?status=not-active') }}" class="dropdown-item">
                            <div class="dropdown-item-icon bg-warning text-white">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                ${item.name}
                                <div class="time">${item.time} <span class="bullet"></span> Belum Diverifikasi</div>
                            </div>
                        </a>`;
                    });
                }

                $('#notif-list').html(html);
            }


            function startPolling() {
                pollingInterval = setInterval(() => {
                    if (!document.hidden) {
                        // getNotification();
                    }
                }, 60000); // 60 detik
            }

            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    clearInterval(pollingInterval); // Hentikan polling saat tab tidak aktif
                } else {
                    startPolling(); // Mulai ulang polling saat tab aktif
                }
            });

            $(document).ready(function() {
                // getNotification(); // Panggil pertama kali saat halaman dimuat
                startPolling(); // Mulai polling jika tab aktif
            });
        </script>
    @endif
</body>

</html>
