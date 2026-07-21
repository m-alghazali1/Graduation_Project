<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    protected $table = 'persons';

    protected $fillable = [
        'national_id',
        'full_name',
        'phone',
        'birth_date',
        'gender',
        'neighborhood_id'
    ];


    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class);
    }
}
