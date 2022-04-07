<?php

namespace App\Repositories\ActivityHistories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityHistory extends Model
{
    protected $table = 'activity_histories';

    protected $guarded = ['id'];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::saving(function ($record) {
            if (Auth::check())
                $record->company_id  = companyID();
        });
    }
}
