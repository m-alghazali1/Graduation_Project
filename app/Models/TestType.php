<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestType extends Model
{
    use HasFactory;
    protected $fillable = [
        'test_name',
        'min_range',
        'max_range'
    ];

    // إذا حبيت مستقبلاً تجيب كل نتائج المختبر المرتبطة بهادا التحليل
    public function labResults()
    {
        return $this->hasMany(LabResult::class);
    }
}
