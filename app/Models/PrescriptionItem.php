<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'medicine_id',
        'dosage',
        'prescribed_quantity',
        'instructions',
        'is_dispensed',
        'dispensed_at',
    ];

    protected $casts = [
        'is_dispensed' => 'boolean',
        'dispensed_at' => 'datetime',
        'prescribed_quantity' => 'integer',
    ];

    // علاقة بند الوصفة بالزيارة
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    // علاقة بند الوصفة بالدواء
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
