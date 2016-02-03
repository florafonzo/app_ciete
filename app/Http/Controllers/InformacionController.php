<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\ContactoRequest;
use Validator;
use Input;
use Mail;

class InformacionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function mision_vision()
	{
		return view('informacion.mision_vision');
	}

    public function estructura()
	{
		return view('informacion.estructura');
	}

    public function servicios()
	{
		return view('informacion.servicios');
	}

    public function equipo()
	{
		return view('informacion.equipo');
	}

    public function getcontacto()
	{
		$show = 'false';
		return \View::make('informacion.contacto', compact('show'));
	}

    public function creditos()
    {
        return view('informacion.creditos');
    }

    public function postContacto(ContactoRequest $request)
    {
    	$show = 'true';

    	$data = $request->only('nombre', 'apellido', 'lugar', 'correo' , 'comentario');

    	$data['messageLines'] = explode("\n", $request->get('comentario'));

	    Mail::send('emails.contacto', $data, function ($message) use ($data) {
	      $message->subject('Contacto:'.$data['nombre'])
	      		  ->to('ciete.app@gmail.com', 'CIETE')
	              ->replyTo($data['correo']);
	    });

	    return \View::make('informacion.contacto', compact('show'));

    	//return back()->withSuccess("Gracias por tu mensaje. Ha sido enviado");
  	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
