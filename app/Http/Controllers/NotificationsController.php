<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\ExpoToken;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.notification.index', compact('notifications'));
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
        $request->validate([
            'title' => 'string|required',
            'body' => 'string|required',
        ]);

        $url = "https://exp.host/--/api/v2/push/send";
        $tokens = ExpoToken::all();
        $arrayTokens = [];

        foreach ($tokens as $token) {
            array_push($arrayTokens, $token->token);
        }

        $data = array(
            'to' => ["ExponentPushToken[FosbZ2PlPwYIauj92Pj7TT]"],
            'title' => $request->title,
            'body' => $request->body,
        );

        $options = array(
            'http' => array(
                'header'  => 
                                "Host: exp.host\r\n".
                                "Accept: application/json\r\n".
                                "Accept-Encoding: gzip, deflate\r\n".
                                "Content-Type:  application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $success = json_decode($result);
        $status_line = $http_response_header[0];

        preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);

        $status = $match[1];

        if ($status !== "200") {
            return redirect()->back()->with('error', 'Hubo un error al enviar la notificación, pruebe mas tarde');
        }

        try {
            $data = $success->data;
            foreach ($data as $response) {
                if ($response->status == "ok") continue;
                if ($response->details->error == "DeviceNotRegistered") {
                    ExpoToken::where('token', $response->details->expoPushToken)->delete();
                }
            }

            $notification = Notification::create($request->all());

            return redirect()->back()->with('status', 'Se envió correctamente la notificación');
        } catch (\Throwable $th) {
            dd($success);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
