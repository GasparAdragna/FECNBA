<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\ExpoToken;
use Illuminate\Http\Request;
use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;

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
        $tokens = ExpoToken::all();
        $arrayTokens = [];

        foreach ($tokens as $token) {
            array_push($arrayTokens, $token->token);
        }

        $message = new ExpoMessage([
                'title' => $request->title,
                'body' => $request->body,
        ]);

        Expo::addDevicesNotRegisteredHandler(function ($tokens) {
            foreach ($tokens as $token) {
                ExpoToken::where('token', $token)->delete();
            }
        });

        $chunks = array_chunk($arrayTokens, 100);

        foreach ($chunks as $chunk) {
            (new Expo)->send($message)->to($chunk)->push();  
        }

        $notification = Notification::create($request->all());

        return redirect()->back()->with('status', 'Se envió correctamente la notificación');
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
