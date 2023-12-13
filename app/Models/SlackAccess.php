<?php

namespace App\Models;

class SlackAccess
{

    private $access_token;
    private $scope;
    private $team_name;
    private $team_id;
    private $incoming_webhook;

    public function __construct( $data )
    {

    }

    public function to_json(): bool|string
    {
        $data = array(
            'access_token' => $this->access_token,
            'scope' => $this->scope,
            'team_name' => $this->team_name,
            'team_id' => $this->team_id,
            'incoming_webhook' => $this->incoming_webhook
        );

        return json_encode( $data );
    }
}
