<?php

namespace App\Mail;

use App\Repositories\Users\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCustomersLimitHours extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    private User $user;
    private array $data;

    public function __construct(User $user, array $data)
    {
        $this->user = $user;
        $this->data = $data;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $info = [
            'user'      => $this->user,
            'company'   => $this->user->company,
            'month'     => ucfirst(now()->monthName),
            'data'      => $this->data
        ];
        return $this->view('emails.customers.limit-hours')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject( "Lista de clientes con tiempo superado.")
            ->with($info);
    }
}
