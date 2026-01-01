
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Selamat Datang</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- CSS Libraries -->
  @stack('custom-css')
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
  
  <style>

    body{
      background-color: #F6F3F3 !important;
    }
    #sidebar-wrapper {
      position: relative;
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .sidebar-menu-wrapper {
      flex: 1;
      overflow-y: auto;
      padding-bottom: 70px;
    }

    .logout-wrapper {
      position: absolute;
      bottom: 20px;
      left: 20px;
      right: 20px;
    }

  </style>
<!-- Start GA -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>
<!-- /END GA --></head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        @include('layouts.navbar')
      </nav>
      
      @include('layouts.sidebar')
        

      <!-- Main Content -->
      <div class="main-content">
        @yield('content')
      </div>
      <footer class="main-footer">
        <div class="footer-left">
          IMPLEMENTASI SISTEM PENDUKUNG KEPUTUSAN SCORING CURRICULUM VITAE MENGGUNAKAN METODE WEIGHTED SCORING MODEL
        </div>
      </footer>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/modules/popper.js') }}"></script>
  <script src="{{ asset('assets/modules/tooltip.js') }}"></script>
  <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
  <script src="{{ asset('assets/js/stisla.js') }}"></script>
  
  <!-- JS Libraies -->
  <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
  <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
  <script src="{{ asset('assets/modules/jquery-ui/jquery-ui.min.js') }}"></script>
  @stack('custom-js')
  <!-- Page Specific JS File -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(Session::has('success'))
    <script>
      Swal.fire({
        title: "Berhasil!",
        text: '{{ Session::get("success") }}',
        icon: "success"
      });
    </script>
    @endif
  <!-- Template JS File -->
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>
  <script>
    setInterval(function() {
      check_lowongan();
    }, 5000);

    function check_lowongan() {
        $.ajax({
            url: "{{ route('job.check-status') }}",
            method: "GET",
            dataType: "json",
            success: function (data) {
              console.log('oke');
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error); // Debug kesalahan
                console.error('Response:', xhr.responseText); // Debug respon server
            }
        });
    }
  </script>
</body>
</html>