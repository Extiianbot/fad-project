<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\StaffUser;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_type',
        'number_of_participants',
        'date_of_event',
        'time_start',
        'time_end',
        'attachment',
        'title_of_event',
        'staff_user_id',
        'is_approved_by',
        'status'
    ];

    protected $casts = [
        'date_of_event' => 'date',
        'time_start' => 'datetime',
        'time_end' => 'datetime',
        'is_approved_by' => 'boolean',
        'status' => 'boolean'
    ];

    public function getTimeStartAttribute($value)
    {
        if ($value === null) return null;
        return $this->asDateTime($value)->format('H:i');
    }

    public function setTimeStartAttribute($value)
    {
        if ($value === null) {
            $this->attributes['time_start'] = null;
            return;
        }
        $this->attributes['time_start'] = $this->fromDateTime($value);
    }

    public function getTimeEndAttribute($value)
    {
        if ($value === null) return null;
        return $this->asDateTime($value)->format('H:i');
    }

    public function setTimeEndAttribute($value)
    {
        if ($value === null) {
            $this->attributes['time_end'] = null;
            return;
        }
        $this->attributes['time_end'] = $this->fromDateTime($value);
    }

    public function staff_user()
    {
        return $this->belongsTo(StaffUser::class, 'staff_user_id', 'id');
    }
}
