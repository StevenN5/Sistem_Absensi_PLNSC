@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
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
        .file-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            background: #eef2ff;
            color: #3730a3;
            text-decoration: none;
        }
        .export-box {
            border: 1px dashed #e6e3ef;
            border-radius: 12px;
            padding: 12px 16px;
            background: #fbfbfe;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">{{ __('global.final_report') }}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('global.home') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('global.final_report') }}</a></li>
        </ol>
    </div>
@endsection

@section('content')
@include('includes.flash')
@php
    if (!function_exists('format_filesize')) {
        function format_filesize($bytes)
        {
            $bytes = (int) $bytes;
            if ($bytes <= 0) {
                return '-';
            }
            $units = ['B', 'KB', 'MB', 'GB'];
            $i = 0;
            while ($bytes >= 1024 && $i < count($units) - 1) {
                $bytes /= 1024;
                $i++;
            }
            return number_format($bytes, 2) . ' ' . $units[$i];
        }
    }
@endphp

    <div class="row">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-body">
                    <div class="export-box mb-3 d-flex align-items-center justify-content-between flex-wrap">
                        <div class="mb-2 mb-md-0">
                            <strong>{{ __('global.export_zip') }}</strong>
                            <span class="text-muted">per bulan</span>
                        </div>
                        <form id="final-report-export-form" class="form-inline">
                            <input type="month" class="form-control mr-2" name="month" required>
                            <button class="btn btn-sm btn-primary" type="submit">{{ __('global.export_zip') }}</button>
                        </form>
                    </div>
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap admin-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th data-priority="1">{{ __('global.date') }}</th>
                                        <th data-priority="2">{{ __('global.employee_id') }}</th>
                                        <th data-priority="3">{{ __('global.name') }}</th>
                                        <th data-priority="4">{{ __('global.final_report_document') }}</th>
                                        <th data-priority="5">{{ __('global.uploaded_by') }}</th>
                                        <th data-priority="6">{{ __('global.file_size') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $report)
                                        <tr>
                                            <td>{{ optional($report->created_at)->format('Y-m-d H:i') }}</td>
                                            <td>{{ optional($report->employee)->id ?? '-' }}</td>
                                            <td>{{ optional($report->employee)->name ?? '-' }}</td>
                                            <td>
                                                <a class="file-pill" href="{{ route('final-report.download', $report->id) }}">
                                                    {{ $report->file_name }}
                                                </a>
                                            </td>
                                            <td>{{ optional($report->uploadedBy)->name ?? '-' }}</td>
                                            <td>{{ format_filesize($report->file_size) }}</td>
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
    <script>
        $(function() {
            $('#final-report-export-form').on('submit', function(e) {
                e.preventDefault();
                var month = $(this).find('[name="month"]').val();
                if (!month) {
                    return;
                }
                window.location.href = "{{ url('/final-report/export') }}/" + month;
            });
        });
    </script>
@endsection
