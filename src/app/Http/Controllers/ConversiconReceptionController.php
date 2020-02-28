<?php

namespace CapeAndBay\Conversicon\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConversiconReceptionController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function message()
    {
        $results = ['success' => false];

        $data = $this->request->all();

        switch(config('conversica.message_driver'))
        {
            case 'log':
                Log::info('Message Received From Conversica -', $data);
                $results = ['success' => true];
                break;

            case 'queue':
                $class_name = config('conversica.message_job_queue.class');
                $queue_name = config('conversica.message_job_queue.queue');
                $class_name::dispatch($data)->onQueue($queue_name);
                break;

            default:
                $results['reason'] = 'Invalid Processing Driver';
        }

        return $results;
    }

    public function lead()
    {
        $results = ['success' => false];

        $data = $this->request->all();

        switch(config('conversica.lead_update_driver'))
        {
            case 'log':
                Log::info('Lead Update Received From Conversica -', $data);
                $results = ['success' => true];
                break;

            case 'queue':
                $class_name = config('conversica.lead_job_queue.class');
                $queue_name = config('conversica.lead_job_queue.queue');
                $class_name::dispatch($data)->onQueue($queue_name);
                break;

            default:
                $results['reason'] = 'Invalid Processing Driver';
        }

        return $results;
    }
}
