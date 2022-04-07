<?php

namespace App\Repositories\History;

use App\Repositories\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserDocument extends Model
{
    protected $table = 'user_documents';

    protected $guarded = ['id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
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
