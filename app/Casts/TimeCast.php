<?php

namespace App\Casts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

trait TimeCast
{
    public function getTimeStartAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        // If value is a full datetime, extract just the time part
        $time = Carbon::parse($value)->format('H:i');
        return $time;
    }

    public function setTimeStartAttribute($value)
    {
        if ($value === null) {
            $this->attributes['time_start'] = null;
            return;
        }

        // Handle time input from form (HH:MM)
        $time = Carbon::parse($value)->format('H:i:s');
        $this->attributes['time_start'] = $time;
    }

    public function getTimeEndAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        // If value is a full datetime, extract just the time part
        $time = Carbon::parse($value)->format('H:i');
        return $time;
    }

    public function setTimeEndAttribute($value)
    {
        if ($value === null) {
            $this->attributes['time_end'] = null;
            return;
        }

        // Handle time input from form (HH:MM)
        $time = Carbon::parse($value)->format('H:i:s');
        $this->attributes['time_end'] = $time;
    }
}
