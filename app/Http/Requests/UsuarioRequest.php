<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioRequest extends Request {

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
            'nombre_contacto' => 'required|max:255',
            'apellido_contacto' => 'required|max:255',
            'lugar_contacto' => 'required|max:255',
            'correo_contacto' => 'required|email|max:255',
            'duda_contacto' => 'required|max:255'
        ];
    }

}
