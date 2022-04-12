<?php

namespace App\Mail\Activities;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

class DeadlineUserMail  extends Mailable implements ShouldQueue
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $info = [
            'user'    => $this->data['user'],
            'company' => $this->data['user']->company,
            'data'    => $this->data,
        ];

        return $this->view('emails.activities.deadline')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject( config('mail.from.activities_deadline')." - ". $this->data['deadline'])
            ->with($info);
    }


}
