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
            'nombre' => 'required|max:255',
            'id_tipo' => 'required',
            'fecha' => 'required',
            'lugar' => 'required|max:100',
            'descripcion' => 'required|max:300',
            'dirigido_a' => 'required|max:300',
            'proposito' => 'required|max:300',
            'modalidad_estrategias' => 'required|max:300',
            'acreditacion' => 'required|max:300',
            'perfil' => 'required|max:300',
            'requerimientos_tec' => 'required|max:300',
            'perfil_egresado' => 'required|max:300',
            'instituciones_aval' => 'required|max:300',
            'aliados' => 'required|max:300',
            'plan_estudio' => 'required|max:300',
            'costo' => 'required|max:300',
            'modalidades_pago' => 'required|max:300',
            'imagen_carrusel' => 'required|mimes:jpeg,png,jpg|max:1024',
            'descripcion_carrusel' => 'required|max:100',
            'activo_carrusel' => 'required',

        ];
    }

}
