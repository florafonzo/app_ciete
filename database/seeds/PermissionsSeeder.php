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

        $crear_usuarios = new Permission();
        $crear_usuarios->name = 'ver_notas';
        $crear_usuarios->display_name = 'ver notas';
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

    }



}