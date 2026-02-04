@extends('layouts.master')

@section('css')
<!-- Table css -->
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css"
    media="screen">
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">{{ __('global.late') }}</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('global.home') }}</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('global.late') }}</a></li>


    </ol>
</div>
@endsection

@section('button')
<a href="/attendance" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>{{ __('global.attendance_table') }}</a>


@endsection

@section('content')
@include('includes.flash')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        
                            <thead>
                                <tr>
                                    <th data-priority="1">{{ __('global.date') }}</th>
                                    <th data-priority="2">{{ __('global.employee_id') }}</th>
                                    <th data-priority="3">{{ __('global.name') }}</th>
                                    <th data-priority="4">{{ __('global.late') }}</th>
                                    <th data-priority="6">{{ __('global.time_in') }}</th>
                                    <th data-priority="7">{{ __('global.time_out') }}</th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($latetimes as $latetime)

                                <tr>
                                    <td>{{ $latetime->latetime_date }}</td>
                                    <td>{{ $latetime->emp_id }}</td>
                                    <td>{{ $latetime->employee->name }}</td>
                                    <td>{{ $latetime->duration }}</td>
                                    <td>{{ $latetime->employee->schedules->first()->time_in }} </td>
                                    <td>{{ $latetime->employee->schedules->first()->time_out }}</td>
                                </tr>

                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection


@section('script')
<!-- Responsive-table-->
<script src="{{ URL::asset('plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js') }}"></script>
@endsection

@section('script')
<script>
    $(function () {
        $('.table-responsive').responsiveTable({
            addDisplayAllBtn: 'btn btn-secondary'
        });
    });
</script>
@endsection
