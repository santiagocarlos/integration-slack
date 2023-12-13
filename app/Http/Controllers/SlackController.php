<?php

namespace App\Http\Controllers;

use App\Models\Slack;
use App\Models\SlackAccess;
use App\Models\SlackResponse;
use App\Models\User;
use Illuminate\Http\Request;

class SlackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function verifyIfExistsDataToUser(User $user): SlackResponse|null
    {
        return SlackResponse::where('user_id', $user->id)->first();
    }

    public function getResponseSlack(Request $request, SlackAccess $access)
    {
        $this->do_oauth($request->input('code'), $access);
    }


    public function do_oauth($code, SlackAccess $access): SlackAccess
    {
        $slack = new Slack($access);

        // Set up the request headers
        $headers = array('Accept' => 'application/json');

        // Add the application id and secret to authenticate the request
        $options = array('auth' => array($this->get_client_id(), $this->get_client_secret()));

        // Add the one-time token to request parameters
        $data = array('code' => $code);

        $response = \WpOrg\Requests\Requests::post($slack->api_root . 'oauth.access', $headers, $data, $options);

        // Handle the JSON response
        $json_response = json_decode($response->body);

        if (! $json_response->ok) {
            // There was an error in the request
            echo "error";
            //throw new Slack_API_Exception( $json_response->error );
        }

        // The action was completed successfully, store and return access data
        return new SlackAccess(
            array(
                'access_token' => $json_response->access_token,
                'scope' => explode( ',', $json_response->scope ),
                'team_name' => $json_response->team_name,
                'team_id' => $json_response->team_id,
                'incoming_webhook' => $json_response->incoming_webhook
            )
        );
    }

   public function get_client_id()
   {
       return config('slack.client_id');
   }

   public function get_client_secret()
   {
       return config('slack.client_id');
   }
}
