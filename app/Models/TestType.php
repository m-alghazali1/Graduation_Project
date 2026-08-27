<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'unit',
        'min_range',
        'max_range',
        'price',
        'description',
        'status',
    ];

    protected $casts = [
        'min_range' => 'float',
        'max_range' => 'float',
        'price' => 'float',
    ];

    // نتائج المختبر المرتبطة بهذا التحليل
    public function labResults()
    {
        return $this->hasMany(LabResult::class, 'test_type_id');
    }
}
