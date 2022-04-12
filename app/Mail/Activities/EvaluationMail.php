<?php

namespace App\Mail\Activities;

use App\Repositories\Users\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EvaluationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $user;
    private $yesterday;
    private $data;

    public function __construct(User $user, array $data)
    {
        $this->user      = $user;
        $this->yesterday = Carbon::yesterday()->format('d/m');
        $this->data      = $data;
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
            'yesterday' => $this->yesterday,
            'data'      => $this->data
        ];
        return $this->view('emails.activities.evaluation')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject( "Actividades por Evaluar! - ". $this->yesterday)
            ->with($info);
    }
}
