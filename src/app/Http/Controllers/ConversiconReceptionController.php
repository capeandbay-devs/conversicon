<?php

namespace CapeAndBay\Conversicon\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ConversiconReceptionController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function message()
    {
        $data = $this->request->all();

        $validated = Validator::make($data, [
            'apiVersion' => 'bail|required',
            'id'         => 'bail|required',
            'clientId'   => 'sometimes|required',
            'action'     => 'bail|required',
            'date'       => 'bail|required',
            'subject'    => 'bail|required',
            'body'       => 'bail|required',
        ]);

        if ($validated->fails())
        {
            return response('Bad Request', 400);
        }

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
                return response('Bad Request', 400);
        }

        return response('OK', 200);
    }

    public function lead()
    {
        $data = $this->request->all();

        $validated = Validator::make($data, [
            'apiVersion'     => 'bail|required',
            'id'             => 'bail|required',
            'dateAdded' => 'bail|required',
            'firstMessageDate'      => 'bail|required',
            'lastMessageDate'          => 'bail|required',
            'lastResponseDate'     => 'bail|required',
            'hotLead'     => 'bail|required',
            'hotLeadDate'     => 'bail|required',
            'leadAtRisk'     => 'bail|required',
            'leadAtRiskDate'     => 'bail|required',
            'actionRequired'     => 'bail|required',
            'actionRequiredDate'     => 'bail|required',
            'leadStatus'     => 'bail|required',
            'leadStatusDate'     => 'bail|required',
            'conversationStage'     => 'bail|required',
            'conversationStageDate'     => 'bail|required',
            'conversationStatus'     => 'bail|required',
            'conversationStatusDate'     => 'bail|required',
            'doNotEmail'     => 'bail|required',
        ]);

        if ($validated->fails())
        {
            return response('Bad Request', 400);
        }

        switch(config('conversica.lead_update_driver'))
        {
            case 'log':
                Log::info('Lead Update Received From Conversica -', $data);
                break;

            case 'queue':
                $class_name = config('conversica.lead_job_queue.class');
                $queue_name = config('conversica.lead_job_queue.queue');
                $class_name::dispatch($data)->onQueue($queue_name);
                break;

            default:
                return response('Bad Request', 400);
        }

        return response('OK', 200);
    }
}
