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

    // علاقة نتيجة المختبر بالزيارة
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    // علاقة نتيجة المختبر بنوع التحليل
    public function testType()
    {
        return $this->belongsTo(TestType::class); // تأكد إنك عامل موديل TestType
    }
}
