<!-- App favicon -->
<link rel="shortcut icon" href="{{ URL::asset('assets/images/') }}">
<meta name="viewport" content="width=device-width, initial-scale=1">      
@yield('css')

 <!-- App css -->
<link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/metismenu.min.css') }}" rel="stylesheet" type="text/css">
<style>
    .logo .logo-text,
    .logo .logo-text-mini {
        display: inline-flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 2px;
        color: #ffffff;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        line-height: 1.1;
    }

    .logo .logo-text span {
        font-size: 18px;
    }

    .logo .logo-text-mini span {
        font-size: 14px;
    }
</style>
<link href="{{ URL::asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/mobile-overrides.css') }}" rel="stylesheet" type="text/css" />

{{-- <link href="{{ URL::asset('plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"> --}}
<link href="{{ asset('plugins/sweetalert.min.css') }}" rel="stylesheet">
<!-- Table css -->
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
<!-- DataTables -->
<link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
