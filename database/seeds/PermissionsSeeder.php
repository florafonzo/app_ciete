<?php namespace database\seeds;

use Illuminate\Database\Seeder;
use App\Models\Permission;


class PermisionsSeeder extends Seeder {

    public function run()
    {
        $ver_usuarios = new Permission();
        $ver_usuarios->name = 'ver_usuarios';
        $ver_usuarios->display_name = 'ver usuarios';
        $ver_usuarios->save();

        $ver_roles = new Permission();
        $ver_roles->name = 'ver_roles';
        $ver_roles->display_name = 'ver roles';
        $ver_roles->save();

        $crear_roles = new Permission();
        $crear_roles->name = 'crear_roles';
        $crear_roles->display_name = 'crear roles';
        $crear_roles->save();

        $crear_usuarios = new Permission();
        $crear_usuarios->name = 'crear_usuarios';
        $crear_usuarios->display_name = 'crear usuarios';
        $crear_usuarios->save();

        $editar_roles = new Permission();
        $editar_roles->name = 'editar_roles';
        $editar_roles->display_name = 'editar roles';
        $editar_roles->save();

        $editar_usuarios = new Permission();
        $editar_usuarios->name = 'editar_usuarios';
        $editar_usuarios->display_name = 'editar usuarios';
        $editar_usuarios->save();

        $eliminar_usuarios = new Permission();
        $eliminar_usuarios->name = 'eliminar_usuarios';
        $eliminar_usuarios->display_name = 'eliminar usuarios';
        $eliminar_usuarios->save();

        $eliminar_roles = new Permission();
        $eliminar_roles->name = 'eliminar_roles';
        $eliminar_roles->display_name = 'eliminar roles';
        $eliminar_roles->save();

        $crear_usuarios = new Permission();
        $crear_usuarios->name = 'ver_lista_cursos';
        $crear_usuarios->display_name = 'ver lista cursos';
        $crear_usuarios->save();

        $crear_usuarios = new Permission();
        $crear_usuarios->name = 'crear_cursos';
        $crear_usuarios->display_name = 'crear cursos';
        $crear_usuarios->save();

        $editar_usuarios = new Permission();
        $editar_usuarios->name = 'editar_cursos';
        $editar_usuarios->display_name = 'editar cursos';
        $editar_usuarios->save();

        $eliminar_roles = new Permission();
        $eliminar_roles->name = 'eliminar_cursos';
        $eliminar_roles->display_name = 'eliminar cursos';
        $eliminar_roles->save();

        $activar_curso = new Permission();
        $activar_curso->name = 'activar_cursos';
        $activar_curso->display_name = 'activar cursos';
        $activar_curso->save();

        $crear_usuarios = new Permission();
        $crear_usuarios->name = 'ver_notas_part';
        $crear_usuarios->display_name = 'ver notas participantes';
        $crear_usuarios->save();

        $crear_usuarios = new Permission();
        $crear_usuarios->name = 'agregar_notas';
        $crear_usuarios->display_name = 'agregar notas';
        $crear_usuarios->save();

        $editar_usuarios = new Permission();
        $editar_usuarios->name = 'editar_notas';
        $editar_usuarios->display_name = 'editar notas';
        $editar_usuarios->save();

        $eliminar_roles = new Permission();
        $eliminar_roles->name = 'eliminar_notas';
        $eliminar_roles->display_name = 'eliminar notas';
        $eliminar_roles->save();

        $eliminar_roles = new Permission();
        $eliminar_roles->name = 'listar_alumnos';
        $eliminar_roles->display_name = 'listar alumnos';
        $eliminar_roles->save();

        $eliminar_roles = new Permission();
        $eliminar_roles->name = 'obtener_certificado';
        $eliminar_roles->display_name = 'obtener  certificado';
        $eliminar_roles->save();

        $crear_usuarios = new Permission();
        $crear_usuarios->name = 'crear_webinars';
        $crear_usuarios->display_name = 'crear webinar';
        $crear_usuarios->save();

        $editar_usuarios = new Permission();
        $editar_usuarios->name = 'editar_webinars';
        $editar_usuarios->display_name = 'editar webinar';
        $editar_usuarios->save();

        $eliminar_roles = new Permission();
        $eliminar_roles->name = 'eliminar_webinars';
        $eliminar_roles->display_name = 'eliminar webinar';
        $eliminar_roles->save();

        $crear_usuarios = new Permission();
        $crear_usuarios->name = 'ver_webinars';
        $crear_usuarios->display_name = 'ver webinar';
        $crear_usuarios->save();

        $ver_perfil_part = new Permission();
        $ver_perfil_part->name = 'ver_perfil_part';
        $ver_perfil_part->display_name = 'ver perfil participante';
        $ver_perfil_part->save();

        $ver_perfil_prof = new Permission();
        $ver_perfil_prof->name = 'ver_perfil_prof';
        $ver_perfil_prof->display_name = 'ver perfil profesor';
        $ver_perfil_prof->save();

        $editar_perfil_part = new Permission();
        $editar_perfil_part->name = 'editar_perfil_part';
        $editar_perfil_part->display_name = 'editar perfil participante';
        $editar_perfil_part->save();

        $editar_perfil_prof = new Permission();
        $editar_perfil_prof->name = 'editar_perfil_profe';
        $editar_perfil_prof->display_name = 'editar perfil profesor';
        $editar_perfil_prof->save();

        $ver_cursos = new Permission();
        $ver_cursos->name = 'ver_cursos_part';
        $ver_cursos->display_name = 'ver cursos participantes';
        $ver_cursos->save();

        $ver_cursos = new Permission();
        $ver_cursos->name = 'ver_cursos_profe';
        $ver_cursos->display_name = 'ver cursos profesores';
        $ver_cursos->save();

        $ver_nota = new Permission();
        $ver_nota->name = 'ver_notas_profe';
        $ver_nota->display_name = 'ver notas profesores';
        $ver_nota->save();
    }

}