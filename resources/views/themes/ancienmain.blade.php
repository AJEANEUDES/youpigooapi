{{-- Le fichier main.blade.php est le fichier principal de la vue du côté admin --}}


<!doctype html>

<html lang="{{ app()->getLocale() }}">

<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">

<title>@yield('title')</title>
<meta content="" name="description">
<meta content="" name="keywords">

<!-- Favicons -->
<link href="http://mycartraders.test:81/themes/img/logo-mct.png" rel="icon">

<!-- Google Fonts -->
<link href="https://fonts.gstatic.com" rel="preconnect">
<link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

<!-- Vendor CSS Files -->
<link href="{{ asset('themes/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('themes/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
<link href="{{ asset('themes/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
<link href="{{ asset('themes/vendor/quill/quill.snow.css') }}" rel="stylesheet">
<link href="{{ asset('themes/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
<link href="{{ asset('themes/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
<link href="{{ asset('themes/vendor/simple-datatables/style.css') }}" rel="stylesheet">

<!-- Template Main CSS File -->
<link href="{{ asset('themes/css/style.css') }}" rel="stylesheet">







<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0px !important;
        Margin: 0px !important;

    }

    div.dataTables_wrapper div.dataTables_length select {
        width: 50% !important;
    }
</style>

</head>



<body>

    <!-- ======= Header ======= -->
    @include('themes.header')

    <!-- ======= Sidebar ======= -->
    @include('themes.sidebar')

    <main id="main" class="main">
        @yield('main')
    </main>

    <!-- ======= Footer ======= -->
    @include('themes.footer')

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>




    <!-- Vendor JS Files -->
    <script src="{{ asset('themes/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('themes/vendor/tinymce/tinymce.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('themes/js/main.js') }}"></script>

    <!-- Vendor JS Files -->
    <script src="{{ asset('js/jQuery-3.6.js') }}"></script>
    <script src="{{ asset('js/utils.min.js') }}"></script>
    <script src="{{ asset('js/sweet-alert.js') }}"></script>
    <script src="{{ asset('js/notiflix-aio-3.2.4.min.js') }}"></script>






    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#myDataTable').DataTable();
        });
    </script>

    <!-- Vendor JS Files -->
    <script src="{{ asset('themes/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('themes/vendor/tinymce/tinymce.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('themes/js/main.js') }}"></script>

    <!-- Vendor JS Files -->
    <script src="{{ asset('js/jQuery-3.6.js') }}"></script>
    <script src="{{ asset('js/utils.min.js') }}"></script>
    <script src="{{ asset('js/sweet-alert.js') }}"></script>
    <script src="{{ asset('js/notiflix-aio-3.2.4.min.js') }}"></script>


    @stack('script-find-chambre')
    @stack('script-find-pays')
    @stack('script-find-ville')
    @stack('script-find-categoriechambre')
    @stack('script-find-hotel')
    @stack('script-find-tyepehebergement')
    @stack('script-services')
    @stack('scripts-images-chambres')
    @stack('scripts-clients')
    @stack('scripts-gestionnaire')
    @stack('scripts-admins')
    @stack('scripts-reservations')
    @stack('scripts-inscription-check-countries')



</body>

</html>
