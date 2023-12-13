<?php

namespace App\Models;

class Slack
{
    public string $api_root = 'https://slack.com/api/';

    public SlackAccess $access;
    public function __construct(SlackAccess $access)
    {
        $this->access = $access;
    }

    public function do_oauth( $code ): SlackAccess
    {
        // Set up the request headers
        $headers = array('Accept' => 'application/json');

        // Add the application id and secret to authenticate the request
        $options = array('auth' => array($this->get_client_id(), $this->get_client_secret()));

        // Add the one-time token to request parameters
        $data = array('code' => $code);

        $response = \WpOrg\Requests\Requests::post($this->api_root . 'oauth.access', $headers, $data, $options);

        // Handle the JSON response
        $json_response = json_decode($response->body);

        if (! $json_response->ok) {
            // There was an error in the request
            echo 'error';
            //throw new Slack_API_Exception( $json_response->error );
        }

        // The action was completed successfully, store and return access data
        $this->access = new SlackAccess(
            array(
                'access_token' => $json_response->access_token,
                'scope' => explode(',', $json_response->scope),
                'team_name' => $json_response->team_name,
                'team_id' => $json_response->team_id,
                'incoming_webhook' => $json_response->incoming_webhook
            )
        );

        return $this->access;
    }

    public function get_client_id()
    {
        return env('SLACK_CLIENT_ID');
    }

    public function get_client_secret()
    {
        return env('SLACK_CLIENT_SECRET');
    }
}
