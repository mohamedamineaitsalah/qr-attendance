<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RejectedScan extends Model
{
    protected $table = 'rejected_scans';

    public $timestamps = false;

    protected $fillable = [
        'person_id',
        'qr_token',
        'reason',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
