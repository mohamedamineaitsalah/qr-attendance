<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';

    public $timestamps = false;

    protected $fillable = [
        'person_id',
        'action',
        'date',
        'time',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function isEntry(): bool
    {
        return $this->action === 'ENTRY';
    }
}
