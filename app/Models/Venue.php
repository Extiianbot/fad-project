<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_name',
        'venue_location',
        'venue_capacity',
        'venue_type',
        'venue_status',
        'user_id',
        'attachment',
    ];
    // Cast date fields and status properly
    protected $casts = [
        'venue_capacity' => 'integer',
        'venue_status' => 'boolean',
    ];
    /**
     * Get the user who requested the venue.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
