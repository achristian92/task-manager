<?php

namespace App\Mail;

use App\Repositories\Users\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailNewUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'user'    => $this->user,
            'company' => $this->user->company
        ];
        return $this->view('emails.users.new-user')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject("Bienvenido a Task Manager ". $this->user->name)
            ->with($data);
    }
}
