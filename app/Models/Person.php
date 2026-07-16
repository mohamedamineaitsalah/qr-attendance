<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Person extends Model
{
    protected $table = 'persons';

    protected $fillable = [
        'first_name',
        'last_name',
        'qr_token',
        'status',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($person) {
            if (empty($person->qr_token)) {
                $person->qr_token = (string) Str::uuid();
            }
        });
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function rejectedScans()
    {
        return $this->hasMany(RejectedScan::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isInside(): bool
    {
        return $this->status === 'INSIDE';
    }
}
