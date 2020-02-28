<?php

namespace CapeAndBay\Conversicon;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Conversica
{
    /**
     * Conversica base url
     *
     * @var string
     */
    private $publicBaseUrl = 'https://integrations-api.conversica.com/json/';

    public function __construct()
    {

    }

    public function fireLead($data = [])
    {
        $results = false;

        // Validate the Data or Fail
        $validated = Validator::make($data, [
            'id'             => 'bail|required',
            'conversationId' => 'bail|required',
            'firstName'      => 'bail|required',
            'email'          => 'bail|required|email',
            'leadSource'     => 'bail|required',

            'leadStatus'     => 'sometimes|required',
            'optOut'         => 'sometimes|required',
            'repId'          => 'sometimes|required',
            'repName'        => 'sometimes|required',
            'clientId'       => 'sometimes|required',
            'lastName'       => 'sometimes|bail|required_with:firstName',
            'homePhone'      => 'sometimes|required',
            'workPhone'      => 'sometimes|required',
            'cellPhone'      => 'sometimes|required',
            'address'        => 'sometimes|required',
            'city'           => 'sometimes|required',
            'state'          => 'sometimes|required',
            'zip'            => 'sometimes|required',
            'leadType'       => 'sometimes|required',
            'date'           => 'sometimes|required',
            'smsOptOut'      => 'sometimes|required',
            'stopMessaging'  => 'sometimes|required',
            'skipToFollowup' => 'sometimes|required',
            'repEmail'       => 'sometimes|required',
        ]);

        if ($validated->fails())
        {
            foreach($validated->errors()->toArray() as $col => $msg)
            {
                Log::info($msg);
                break;
            }
        }
        else
        {
            $data['apiVersion'] = config('conversica.api_version');

            // Send the Data to conversica or fail.
            $auth_user = config('conversica.deets.username');
            $auth_pass = config('conversica.deets.password');

            // Send the Data to conversica or fail.
            $response = Curl::to($this->publicBaseUrl)
                ->withContentType('application/json')
                ->withOption('USERPWD', $auth_user.':'.$auth_pass)
                ->withData($data)
                ->asJsonRequest(true)
                ->post();

            Log::info('Conversica Response - ', [$response]);

            if($response)
            {
                $results = $response;
            }
        }

        return $results;
    }
}
