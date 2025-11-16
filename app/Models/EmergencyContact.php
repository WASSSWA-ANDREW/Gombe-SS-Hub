<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'phone',
        'whatsapp',
        'email',
        'available_24_7',
        'available_hours',
        'priority',
        'is_active'
    ];

    protected $casts = [
        'available_24_7' => 'boolean',
        'is_active' => 'boolean',
        'priority' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Scope for active contacts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering by priority
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority');
    }
}