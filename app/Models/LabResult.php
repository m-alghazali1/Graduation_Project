<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'result_value',
        'lab_notes',
        'status',
        'visit_id',
        'test_type_id',
    ];

    protected $casts = [
        'result_value' => 'float',
    ];

    // علاقة نتيجة المختبر بالزيارة
    public function visit()
    {
        return $this->belongsTo(Visit::class, 'visit_id');
    }

    // علاقة نتيجة المختبر بنوع التحليل
    public function testType()
    {
        return $this->belongsTo(TestType::class, 'test_type_id');
    }
}
