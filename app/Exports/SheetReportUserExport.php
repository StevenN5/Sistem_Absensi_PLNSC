<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SheetReportUserExport implements FromArray, WithEvents
{
    private Carbon $month;
    private int $daysInMonth;
    private string $monthLabel;
    private Employee $employee;

    public function __construct(?string $month, Employee $employee)
    {
        if ($month) {
            try {
                $parsed = Carbon::createFromFormat('Y-m', $month);
                $this->month = $parsed->startOfMonth();
            } catch (\Exception $e) {
                $this->month = Carbon::today()->startOfMonth();
            }
        } else {
            $this->month = Carbon::today()->startOfMonth();
        }

        $this->daysInMonth = $this->month->daysInMonth;
        Carbon::setLocale(app()->getLocale());
        $this->monthLabel = $this->month->translatedFormat('F Y');
        $this->employee = $employee;
    }

    public function filename(): string
    {
        return 'SheetReport-' . $this->employee->id . '-' . $this->month->format('Y-m') . '.xlsx';
    }

    public function array(): array
    {
        $start = $this->month->copy()->startOfMonth()->toDateString();
        $end = $this->month->copy()->endOfMonth()->toDateString();

        $attendance = Attendance::query()
            ->where('emp_id', $this->employee->id)
            ->whereBetween('attendance_date', [$start, $end])
            ->get()
            ->groupBy(function ($row) {
                return $row->attendance_date;
            });

        $leave = Leave::query()
            ->where('emp_id', $this->employee->id)
            ->whereBetween('leave_date', [$start, $end])
            ->get()
            ->groupBy(function ($row) {
                return $row->leave_date;
            });

        $rows = [];
        $rows[] = ['', $this->monthLabel, '', '', '', '', '', ''];
        $rows[] = [
            '',
            __('global.date'),
            'No.',
            __('global.employee_name'),
            __('global.in'),
            __('global.out'),
            __('global.status'),
            __('global.note'),
        ];

        $counter = 1;
        for ($i = 1; $i <= $this->daysInMonth; $i++) {
            $date = $this->month->copy()->day($i)->format('Y-m-d');

            $attd = $attendance->get($date);
            $leaveRow = $leave->get($date);

            if (!$attd && !$leaveRow) {
                continue;
            }

            $timeIn = '-';
            if ($attd && $attd->first() && $attd->first()->attendance_time) {
                $timeIn = Carbon::parse($attd->first()->attendance_time)->format('H:i');
            }

            $timeOut = '-';
            if ($leaveRow && $leaveRow->first() && $leaveRow->first()->leave_time) {
                $timeOut = Carbon::parse($leaveRow->first()->leave_time)->format('H:i');
            }

            $status = __('global.no_data');
            if ($attd && $attd->first()) {
                $statusType = $attd->first()->status_type;
                if ($statusType === 'sakit') {
                    $status = __('global.sick');
                } elseif ($statusType === 'izin') {
                    $status = __('global.permission');
                } elseif ($statusType === 'tanpa_keterangan') {
                    $status = __('global.without_note');
                } else {
                    $status = $attd->first()->status === 0
                        ? __('global.telat_pulang_cepat')
                        : __('global.hadir_tepat');
                }
            } elseif ($leaveRow && $leaveRow->first()) {
                $status = $leaveRow->first()->status === 0
                    ? __('global.telat_pulang_cepat')
                    : __('global.hadir_tepat');
            }

            $noteText = '';
            if ($attd && $attd->first() && $attd->first()->note) {
                $noteText = $attd->first()->note;
            } elseif ($leaveRow && $leaveRow->first() && $leaveRow->first()->note) {
                $noteText = $leaveRow->first()->note;
            }

            $rows[] = [
                '',
                $date,
                $counter,
                $this->employee->name,
                $timeIn,
                $timeOut,
                $status,
                $noteText,
            ];
            $counter++;
        }

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->mergeCells('B1:H1');

                $sheet->getColumnDimension('A')->setWidth(4);
                $sheet->getColumnDimension('B')->setWidth(26);
                $sheet->getColumnDimension('C')->setWidth(4);
                $sheet->getColumnDimension('D')->setWidth(26);
                $sheet->getColumnDimension('E')->setWidth(9);
                $sheet->getColumnDimension('F')->setWidth(9);
                $sheet->getColumnDimension('G')->setWidth(12);
                $sheet->getColumnDimension('H')->setWidth(28);

                $sheet->getStyle('B1:H' . $lastRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                $sheet->getStyle('A1:A' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_NONE,
                        ],
                    ],
                ]);
            },
        ];
    }
}
