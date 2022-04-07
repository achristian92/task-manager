<?php

namespace App\Repositories\SubActivities;

use App\Repositories\Activities\Activity;
use Illuminate\Database\Eloquent\Model;

class SubActivity extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
