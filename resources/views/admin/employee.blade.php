@extends('layouts.master')

@section('css')
<style>
    .admin-card {
        border-radius: 16px;
        border: 1px solid #e6e3ef;
        box-shadow: 0 16px 32px rgba(15, 23, 42, 0.06);
    }
    .admin-card .card-body {
        padding: 20px;
    }
    .admin-table thead th {
        background: #f7f5fb;
        color: #5b6073;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid #e6e3ef;
    }
    .admin-table tbody td {
        vertical-align: middle;
    }
    .admin-table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #d6dbe6;
    }
    .admin-table th,
    .admin-table td {
        border: 1px solid #d6dbe6;
    }
    .admin-table .btn {
        border-radius: 10px;
        font-weight: 600;
    }
</style>
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">{{ __('global.employees') }}</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('global.home') }}</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('global.employees') }}</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('global.employees_list') }}</a></li>
  
    </ol>
</div>
@endsection
@section('button')
<a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>{{ __('global.add') }}</a>
        

@endsection

@section('content')
@include('includes.flash')
<!--Show Validation Errors here-->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!--End showing Validation Errors here-->


                      <div class="row">
                            <div class="col-12">
                                <div class="card admin-card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="datatable-employees" class="table table-striped table-bordered dt-responsive nowrap admin-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        
                                                    <thead>
                                                    <tr>
                                                        <th data-priority="1">{{ __('global.employee_id') }}</th>
                                                        <th data-priority="2">{{ __('global.name') }}</th>
                                                        <th data-priority="3">{{ __('global.phone_number') }}</th>
                                                        <th data-priority="4">{{ __('global.address') }}</th>
                                                        <th data-priority="5">{{ __('global.birth_date') }}</th>
                                                        <th data-priority="6">{{ __('global.institution') }}</th>
                                                        <th data-priority="7">{{ __('global.position') }}</th>
                                                        <th data-priority="8">{{ __('global.major') }}</th>
                                                        <th data-priority="9">{{ __('global.email') }}</th>
                                                        <th data-priority="10">{{ __('global.schedule_label') }}</th>
                                                        <th data-priority="11">{{ __('global.member_since') }}</th>
                                                        <th data-priority="12">{{ __('global.actions') }}</th>
                                                     
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach( $employees as $employee)

                                                        <tr>
                                                            <td>{{$employee->id}}</td>
                                                            <td>{{$employee->name}}</td>
                                                            <td>{{$employee->phone_number}}</td>
                                                            <td>{{$employee->address}}</td>
                                                            <td>{{$employee->birth_date}}</td>
                                                            <td>{{$employee->institution}}</td>
                                                            <td>{{$employee->position}}</td>
                                                            <td>{{$employee->major}}</td>
                                                            <td>{{$employee->email}}</td>
                                                            <td>
                                                                @if(isset($employee->schedules->first()->slug))
                                                                {{$employee->schedules->first()->slug}}
                                                                @endif
                                                            </td>
                                                            <td>{{$employee->created_at}}</td>
                                                            <td>
                        
                                                                <a href="#edit{{$employee->id}}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat"><i class='fa fa-edit'></i> Ubah</a>
                                                                <a href="#delete{{$employee->id}}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat"><i class='fa fa-trash'></i> Hapus</a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                   
                                                    </tbody>
                                                </table>
                                        </div>
                                    </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->    
                                    

@foreach( $employees as $employee)
@include('includes.edit_delete_employee')
@endforeach

@include('includes.add_employee')

@endsection


@section('script')
<script>
    $(function () {
        var table = $('#datatable-employees').DataTable({
            lengthChange: false,
            buttons: [
                'copy',
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var styles = xlsx.xl['styles.xml'];

                        // Add a border style
                        var borders = $('borders', styles);
                        var borderCount = borders.attr('count');
                        borders.append(
                            '<border>' +
                                '<left style="thin"><color auto="1"/></left>' +
                                '<right style="thin"><color auto="1"/></right>' +
                                '<top style="thin"><color auto="1"/></top>' +
                                '<bottom style="thin"><color auto="1"/></bottom>' +
                                '<diagonal/>' +
                            '</border>'
                        );
                        borders.attr('count', parseInt(borderCount, 10) + 1);

                        // Add a cellXfs style that uses the new border
                        var cellXfs = $('cellXfs', styles);
                        var xfCount = cellXfs.attr('count');
                        cellXfs.append(
                            '<xf xfId="0" applyBorder="1" borderId="' + borderCount + '"/>'
                        );
                        cellXfs.attr('count', parseInt(xfCount, 10) + 1);
                        var borderStyleIndex = xfCount;

                        // Apply border style to all cells
                        $('row c', sheet).attr('s', borderStyleIndex);

                        // Set column widths: Nama = 50, others = 20
                        var cols = $('cols', sheet);
                        if (cols.length === 0) {
                            cols = $('<cols/>').prependTo(sheet);
                        } else {
                            cols.empty();
                        }

                        // Column order matches table headers (12 columns)
                        var widths = [20, 50, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20];
                        for (var i = 0; i < widths.length; i++) {
                            cols.append(
                                '<col min="' + (i + 1) + '" max="' + (i + 1) + '" width="' + widths[i] + '" customWidth="1"/>'
                            );
                        }
                    }
                },
                'pdf',
                'colvis'
            ]
        });

        table.buttons().container()
            .appendTo('#datatable-employees_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection
