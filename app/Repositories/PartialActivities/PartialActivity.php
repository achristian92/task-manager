<?php

namespace App\Repositories\PartialActivities;

use App\Repositories\Activities\Activity;
use Illuminate\Database\Eloquent\Model;

class PartialActivity extends Model
{
    protected $table = 'partial_activities';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
