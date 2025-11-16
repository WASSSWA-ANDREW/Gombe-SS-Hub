<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'user_name',
        'user_email',
        'category',
        'priority',
        'subject',
        'description',
        'attachments',
        'contact_method',
        'status',
        'assigned_to',
        'internal_notes',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'attachments' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that created the ticket
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get ticket responses
     */
    public function responses()
    {
        return $this->hasMany(SupportTicketResponse::class);
    }

    /**
     * Scope for open tickets
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope for urgent tickets
     */
    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }
}