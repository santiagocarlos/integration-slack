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

    public function getResponseSlack(Slack $slack, Request $request): void
    {
        $this->do_action($slack, $request);
    }


    private function do_action(Slack $slack, Request $request): void
    {
        $result_message = '';

        switch ($request->input('action')) {

            // Handles the OAuth callback by exchanging the access code to
            // a valid token and saving it in a file
            case 'oauth':
                $code = $request->input('code');

                // Exchange code to valid access token
                try {
                    $access = $slack->do_oauth($code);
                    dd($access);
                    if ( $access ) {
                        file_put_contents( 'access.txt', $access->to_json() );
                        $result_message = 'The application was successfully added to your Slack channel';
                    }
                } catch ( Slack_API_Exception $e ) {
                    $result_message = $e->getMessage();
                }
                break;

            // Sends a notification to Slack
            case 'send_notification':
                $message = isset( $_REQUEST['text'] ) ? $_REQUEST['text'] : 'Hello!';

                try {
                    $slack->send_notification( $message );
                    $result_message = 'Notification sent to Slack channel.';
                } catch ( Slack_API_Exception $e ) {
                    $result_message = $e->getMessage();
                }
                break;

            // Responds to a Slack slash command. Notice that commands are registered
            // at Slack initialization.
            case 'command':
                $slack->do_slash_command();
                break;

            default:
                break;

        }

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
