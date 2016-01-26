<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class ContactoRequest extends Request {

    public function authorize()
    {
        return true;
    }

	/**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'nombre' => 'required|max:255',
            'apellido' => 'required|max:255',
            'lugar' => 'required|max:255',
            'correo' => 'required|email|max:255',
            'comentario' => 'required|max:255',
        ];
    }
}
