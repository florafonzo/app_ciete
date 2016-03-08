<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class WebinarRequest extends Request {

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
//        dd();
        return [
            'nombre' => 'required|max:255|unique:webinars',
            'secciones' => 'required|integer|min:1',
            'mini' => 'required|integer|min:1',
            'maxi' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'especificaciones' => 'required|max:1000',
//            'duracion' => 'required|integer',
//            'lugar' => 'required|max:100',
//            'descripcion' => 'required|max:1000',
//            'link' => 'required',
            'imagen_carrusel' => 'mimes:jpeg,png,jpg|max:1024',
            'descripcion_carrusel' => 'max:100',
            'activo_carrusel' => '',

        ];
    }

}
