<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <title>Sistem Manajemen Absensi</title>
        <meta content="Dasbor Admin" name="description" />
        <meta content="Themesbrand" name="author" />
        <link rel="shortcut icon" href="assets/images/">
        @include('layouts.head')
        @yield('css')
  </head>
    <body class="pb-0" >
        @yield('content')
        @include('layouts.footer-script')    
        @include('includes.flash')
    </body>
</html>
