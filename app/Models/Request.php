<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status',
        'is_approved_by',
        'created_at',
        'updated_at',
        'staff_user_id'
    ];

    protected $casts = [
        'is_approved_by' => 'boolean',
        'status' => 'boolean'
    ];

    // Relationship to Venue
    public function venue()
    {
        return $this->hasOne(Venue::class);
    }

    // Relationship to Transportation
    public function transportation()
    {
        return $this->hasOne(Transportation::class);
    }

    // Relationship to StaffUser
    public function staffUser()
    {
        return $this->belongsTo(StaffUser::class);
    }

    // Get formatted status
    public function getStatusDisplayAttribute()
    {
        if (!$this->is_approved_by && !$this->status) {
            return 'Pending';
        } elseif ($this->is_approved_by && $this->status) {
            return 'Approved';
        } else {
            return 'Rejected';
        }
    }
}
