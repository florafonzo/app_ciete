<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class CursoRequest extends Request {

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
            'nombre' => 'required|max:255|unique:cursos',
            'secciones' => 'required|integer|min:1',
            'mini' => 'required|integer|min:1',
            'maxi' => 'required|integer|min:1',
            'id_tipo' => 'required',
            'id_modalidad_curso' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
//            'duracion' => 'required|integer',
//            'lugar' => 'required|max:100',
            'especificaciones' => 'required',
//            'dirigido_a' => 'required|max:1000',
//            'proposito' => 'required|max:1000',
//            'modalidad_estrategias' => 'required|max:1000',
//            'acreditacion' => 'required|max:1000',
//            'perfil' => 'required|max:1000',
//            'requerimientos_tec' => 'required|max:1000',
//            'perfil_egresado' => 'required|max:1000',
//            'instituciones_aval' => 'required|max:1000',
//            'aliados' => 'required|max:1000',
//            'plan_estudio' => 'required|max:1000',
            'costo' => 'required|integer',
//            'modalidades_pago' => 'required|max:1000',
            'imagen_carrusel' => 'mimes:jpeg,png,jpg|max:1024',
            'descripcion_carrusel' => 'max:100',
            'activo_carrusel' => '',

        ];
    }

}
