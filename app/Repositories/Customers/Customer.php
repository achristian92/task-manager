<?php

namespace App\Repositories\Customers;

use App\Models\User;
use App\Repositories\Activities\Activity;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use softDeletes, HasFactory;

    protected $appends = ['src_logo'];

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function getSrcLogoAttribute(): string
    {
        return $this->src_img ?: asset('img/customer-default.png');
    }

    public function activities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Activity::class);
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
