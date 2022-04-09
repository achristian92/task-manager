<?php

namespace App\Mail;

use App\Repositories\Activities\Activity;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailActivityAssigned extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    private Activity $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'toUser'       => $this->activity->user->full_name,
            'createdBy'    => $this->activity->created_by->full_name,
            'activity'     => $this->activity->name,
            'customer'     => $this->activity->customer->name,
            'timeEstimate' => $this->activity->time_estimate,
            'date'         => Carbon::parse($this->activity->start_date)->format('d/m'),
            'status'       => $this->activity->statusName(),
        ];

        return $this->view('emails.users.assigned-activity')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject( config('mail.from.new_activity_for_you')." - ". $data['activity'])
            ->with([
                'data'    => $data,
                'company' => $this->activity->user->company
            ]);
    }
}
