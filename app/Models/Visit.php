<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_date',
        'blood_pressure',
        'weight',
        'temperature',
        'doctor_notes',
        'status',
        'person_id',
        'doctor_id',
    ];

    // عشان لارافيل يتعامل مع هذا الحقل كتاريخ ووقت مش كنص عادي
    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    // علاقة الزيارة بالمريض
    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    // علاقة الزيارة بالطبيب
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
