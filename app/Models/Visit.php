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
        'diagnosis',
        'doctor_notes',
        'status',
        'person_id',
        'doctor_id',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'weight' => 'float',
        'temperature' => 'float',
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

    // التحاليل المخبرية المطلوبة في هذه الزيارة
    public function labResults()
    {
        return $this->hasMany(LabResult::class, 'visit_id');
    }

    // الوصفة الطبية (الأدوية الموصوفة) في هذه الزيارة
    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class, 'visit_id');
    }
}
