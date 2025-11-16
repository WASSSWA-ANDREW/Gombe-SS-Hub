<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'contact_type',
        'reason',
        'message',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that initiated the emergency contact
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}