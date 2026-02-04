<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalReport extends Model
{
    use HasFactory;

    protected $table = 'final_reports';

    protected $fillable = [
        'emp_id',
        'uploaded_by',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
