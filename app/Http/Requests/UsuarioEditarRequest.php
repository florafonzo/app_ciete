<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioEditarRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::check()){
            return true;
        }else{
            return false;
        }
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
            'documento_identidad' => 'required|max:50',
//            'id_rol' => 'required',
            'telefono' => 'required|max:20',
            'celular' => 'max:20',
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed|min:6',
            'email_alternativo' => 'email|max:255|unique:users',
            'imagen' => 'mimes:jpeg,png,jpg|max:1024',
        ];
    }

}
