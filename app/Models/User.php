<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // الحقول المسموح إدخالها
    protected $fillable = [
        'person_id',
        'email',
        'password',
        'role',
    ];

    // إخفاء الباسورد والتوكن عند إرجاع البيانات عشان الحماية (Security)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // تشفير تلقائي أو تحويل أنواع البيانات
    protected $casts = [
        'password' => 'hashed',
    ];

    // علاقة المستخدم بالبيانات الشخصية (كل مستخدم له شخص واحد)
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
