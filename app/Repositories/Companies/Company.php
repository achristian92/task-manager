<?php

namespace App\Repositories\Companies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['src_logo'];

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getSrcLogoAttribute(): string
    {
        return $this->src_img ?: asset('img/task-manager-logo.png');
    }

}
