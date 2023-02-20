<?php

namespace App\Jobs;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;

class ProcessWebhookJob extends SpatieProcessWebhookJob
{
    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    public function handle()
    {
        //You can perform an heavy logic here
        logger($this->webhookCall['payload']);
//        sleep(10);
//        logger("I am done");
    }
}
