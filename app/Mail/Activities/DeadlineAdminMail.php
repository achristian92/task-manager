<?php

namespace App\Mail\Activities;

use App\Repositories\Users\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

class DeadlineAdminMail extends Mailable implements ShouldQueue
{
    private array $data;
    private string $tomorrow;
    private User $user;

    public function __construct(User $user, array $data)
    {
        $this->user = $user;
        $this->data = $data;
        $this->tomorrow = Carbon::tomorrow()->format('d/m');

    }

    public function build()
    {
        $info = [
            'user'     => $this->user,
            'company'  => $this->user->company,
            'tomorrow' => $this->tomorrow,
            'data'     => $this->data,
        ];

        return $this->view('emails.activities.deadline-admin')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject( config('mail.from.activities_deadline')." - ". $this->tomorrow)
            ->with($info);
    }
}
