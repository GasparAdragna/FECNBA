<?php

namespace App\Http\Controllers;

Use App\Models\Category;
Use App\Models\State;
Use App\Models\Fecha;
Use App\Models\Contact;
use Mail;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('show', 'destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::latest('dia')->first();
        return view('torneo.contacto', compact('estado', 'categorias', 'fecha'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Category::all();
        $estado = State::where('active', true)->first();
        $fecha = Fecha::latest('dia')->first();
        return view('torneo.contacto', compact('estado', 'categorias', 'fecha'));
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
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'email' => 'email|required', 
            'message' => 'string|required',
            'g-recaptcha-response' => 'required'
        ]);

        if(env('APP_ENV') == "local"){
            Contact::create($request->all());
            $data = array('consulta'=>$request);
            Mail::send('consulta', $data, function($message){
            $message->to('gaspar.jac@hotmail.com', 'FECNBA')->subject
                ('¡Nueva consulta en el sitio!');
            $message->from('noreply@fecnba.com.ar','FECNBA');
            });

            return redirect()->back()->with('status', 'Se envió correctamente la consulta');

        }
        $recaptcha = $request->input('g-recaptcha-response');
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
          'secret' => env('GOOGLE_CAPTCHA'),
          'response' => $recaptcha
        );
        $options = array(
          'http' => array (
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
          )
        );
        $context  = stream_context_create($options);
        $verify = file_get_contents($url, false, $context);
        $captcha_success = json_decode($verify);
        if ($captcha_success->success) {
            Contact::create($request->all());
            $data = array('consulta'=>$request);
            Mail::send('consulta', $data, function($message){
            $message->to('gaspar.jac@hotmail.com', 'FECNBA')->subject
                ('¡Nueva consulta en el sitio!');
            $message->from('noreply@fecnba.com.ar','FECNBA');
            });

            return redirect()->back()->with('status', 'Se envió correctamente la consulta');
        } else {
          return redirect()->back()->with('error', 'No se pudo enviar su consulta. Intente nuevamente');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::find($id);
        return view('admin.contact.show', compact('contact'));
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
}
