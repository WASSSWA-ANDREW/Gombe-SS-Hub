<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'category',
        'subject',
        'message',
        'priority',
        'page_url',
        'browser_info',
        'screenshot_path',
        'contact_email',
        'allow_contact',
        'ip_address',
        'user_agent',
        'status',
        'admin_response',
        'updated_by'
    ];

    protected $casts = [
        'allow_contact' => 'boolean',
        'browser_info' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that submitted the feedback
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin user who updated the feedback
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for open feedback
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope for resolved feedback
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }
}