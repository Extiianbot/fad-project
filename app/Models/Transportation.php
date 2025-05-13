<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transportation extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination',
        'number_of_passengers',
        'purpose',
        'date_time_needed',
        'date_time_returned',
        'is_approved_by',
        'status',
        'attachment',
        'user_id',
    ];

        // Cast date fields and status properly
    protected $casts = [
        'date_time_needed' => 'datetime',
        'date_time_returned' => 'datetime',
        'is_approved_by' => 'boolean',
        'status' => 'boolean',
    ];

    
    /**
     * Get the user who requested the transportation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
