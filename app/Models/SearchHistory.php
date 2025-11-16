<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'query',
        'filters',
        'results_count',
        'search_type',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'filters' => 'array',
        'results_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user who performed the search
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for recent searches
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for popular searches
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->selectRaw('query, COUNT(*) as search_count')
                    ->groupBy('query')
                    ->orderByDesc('search_count')
                    ->limit($limit);
    }

    /**
     * Scope for user searches
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}