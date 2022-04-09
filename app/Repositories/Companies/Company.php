<?php

namespace App\Repositories\Companies;

use App\Repositories\Users\User;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['src_logo'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getSrcLogoAttribute(): string
    {
        return $this->src_img ?: asset('img/task-manager-logo.png');
    }

    public function notifyAssignmentActivity(): bool
    {
        return $this->notify_assignment && config('mail.from.send_email');
    }

    public function notifyOverdueActivities(): bool
    {
        return $this->send_overdue && config('mail.from.send_email');
    }
    public function notifyCredentialsUser(): bool
    {
        return $this->send_credentials && config('mail.from.send_email');
    }

    public function notifyDeadlineActivities(): bool
    {
        return $this->notify_deadline && config('mail.from.send_email');

    }

}
