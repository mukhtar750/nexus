<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimedCertificate extends Model
{
    protected $fillable = [
        'user_id',
        'module_id',
        'module_title',
        'claimed_at',
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
