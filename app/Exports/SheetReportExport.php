<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SheetReportExport implements FromArray, WithEvents
{
    private Carbon $month;
    private int $daysInMonth;
    private string $monthLabel;

    public function __construct(?string $month)
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
    }

    public function filename(): string
    {
        return 'SheetReport-All-' . $this->month->format('Y-m') . '.xlsx';
    }

    public function array(): array
    {
        $employees = Employee::all();
        $start = $this->month->copy()->startOfMonth()->toDateString();
        $end = $this->month->copy()->endOfMonth()->toDateString();

        $attendance = Attendance::query()
            ->whereBetween('attendance_date', [$start, $end])
            ->get()
            ->groupBy(function ($row) {
                return $row->emp_id . '|' . $row->attendance_date;
            });

        $leave = Leave::query()
            ->whereBetween('leave_date', [$start, $end])
            ->get()
            ->groupBy(function ($row) {
                return $row->emp_id . '|' . $row->leave_date;
            });

        $rows = [];
        $totalColumns = 3 + ($this->daysInMonth * 4);

        $monthRow = array_fill(0, $totalColumns, '');
        $monthRow[0] = $this->monthLabel;
        $rows[] = $monthRow;

        $dateHeader = [
            __('global.employee_name'),
            __('global.employee_position'),
            __('global.employee_id'),
        ];
        for ($i = 1; $i <= $this->daysInMonth; $i++) {
            $dateHeader[] = $this->month->copy()->day($i)->format('Y-m-d');
            $dateHeader[] = '';
            $dateHeader[] = '';
            $dateHeader[] = '';
        }
        $rows[] = $dateHeader;

        $subHeader = ['', '', ''];
        for ($i = 1; $i <= $this->daysInMonth; $i++) {
            $subHeader[] = __('global.in');
            $subHeader[] = __('global.out');
            $subHeader[] = __('global.status');
            $subHeader[] = __('global.note');
        }
        $rows[] = $subHeader;

        foreach ($employees as $employee) {
            $row = [
                $employee->name,
                $employee->position,
                $employee->id,
            ];

            for ($i = 1; $i <= $this->daysInMonth; $i++) {
                $date = $this->month->copy()->day($i)->format('Y-m-d');
                $key = $employee->id . '|' . $date;

                $attd = $attendance->get($key);
                $leaveRow = $leave->get($key);

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

                $row[] = $timeIn;
                $row[] = $timeOut;
                $row[] = $status;
                $row[] = $noteText;
            }

            $rows[] = $row;
        }

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $totalColumns = 3 + ($this->daysInMonth * 4);
                $lastColumn = Coordinate::stringFromColumnIndex($totalColumns);
                $lastRow = $sheet->getHighestRow();
                $range = 'A1:' . $lastColumn . $lastRow;

                $sheet->mergeCells('A1:' . $lastColumn . '1');

                $sheet->getColumnDimensionByColumn(1)->setWidth(26);
                $sheet->getColumnDimensionByColumn(2)->setWidth(26);
                $sheet->getColumnDimensionByColumn(3)->setWidth(10);
                for ($col = 4; $col <= $totalColumns; $col++) {
                    $offset = ($col - 4) % 4;
                    $sheet->getColumnDimensionByColumn($col)->setWidth($offset < 2 ? 9 : 18);
                }

                for ($i = 0; $i < $this->daysInMonth; $i++) {
                    $startCol = 4 + ($i * 4);
                    $endCol = $startCol + 3;
                    $startLetter = Coordinate::stringFromColumnIndex($startCol);
                    $endLetter = Coordinate::stringFromColumnIndex($endCol);
                    $sheet->mergeCells($startLetter . '2:' . $endLetter . '2');
                }

                $sheet->getStyle($range)->applyFromArray([
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

            },
        ];
    }
}
