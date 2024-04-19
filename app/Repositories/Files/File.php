<?php

namespace App\Repositories\Files;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class File extends Model
{
    protected $guarded = ['id'];

    public function fileable()
    {
        return $this->morphTo();
    }
    public static function boot()
    {
        parent::boot();

        static::saving(function ($record) {
            if (Auth::check())
                $record->company_id  = companyID();
        });
    }
}
