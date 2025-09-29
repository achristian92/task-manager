<?php

namespace App\Repositories\Users;

use App\Repositories\Activities\Activity;
use App\Repositories\Companies\Company;
use App\Repositories\Customers\Customer;
use App\Repositories\Documents\UserDocument;
use App\Repositories\Histories\UserHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use  HasFactory, Notifiable, HasRoles;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['full_name','is_admin_or_supervisor','is_admin'];

    public function getFullNameAttribute()
    {
        return ucwords("{$this->name} {$this->last_name}");
    }

    public function getIsAdminOrSupervisorAttribute(): bool
    {
        return $this->isAdmin() || $this->isSupervisor();
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->isAdmin();
    }

    public function isAdminOrSupervisor(): bool
    {
        return $this->isAdmin() || $this->isSupervisor();
    }

    public function isSuperAdmin(): bool
    {
        return $this->email === 'aruiz@tavera.pe';
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    public function isSupervisor(): bool
    {
        return $this->hasRole('Supervisor');
    }
    public function isCollaborator(): bool
    {
        return $this->hasRole('Usuario');
    }

    public function history()
    {
        return $this->hasMany(UserHistory::class)->orderBy('created_at','desc');
    }
    public function documents()
    {
        return $this->hasMany(UserDocument::class)->orderBy('created_at','desc');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function clients()
    {
        return $this->hasMany(Customer::class,'user_id');
    }
    public function supervise()
    {
        return $this->belongsToMany(User::class,'supervisor_user','user_id','supervisor_id');
    }
    public function customers()
    {
        return $this->belongsToMany(Customer::class,'customer_user');
    }

    public function urlImg()
    {
        return $this->src_img ?:  asset('img/user-default.png');
    }

    public function lastLogin()
    {
        return $this->last_login ? Carbon::parse($this->last_login)->format('d/m/Y H:i')." última conexión" : "Aún no ingresa al sistema";
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
