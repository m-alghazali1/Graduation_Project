<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'strength',
        'stock_quantity',
        'is_available',
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
        'is_available' => 'boolean',
    ];

    // الوصفات التي تحتوي على هذا الدواء
    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class, 'medicine_id');
    }
}
