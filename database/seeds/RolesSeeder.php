<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
class RolesSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();

//        Rol Adminnistrador--------------------------
        $admin = Role::create(array(
            'name' => 'admin',
            'display_name' => 'Administrador',
            'description' => 'Rol que posee todos los permisos de la aplicación'
        ));

//        $permisos = Permission::all();
//        foreach($permisos as $permiso){
//            $admin->attachPermission($permiso);
//        }


//      Rol Coordinador ---------------------
        $coord = Role::create(array(
            'name' => 'coordinador',
            'display_name' => 'Coordinador',
            'description' => 'Rol que posee solo permisos para gestionar los cursos, notas y listas de alumnos'
        ));


//      Rol Participante ---------------------------
        $participante = Role::create(array(
            'name' => 'participante',
            'display_name' => 'Participante',
            'description' => 'Rol que posee solo permisos para ver sus notas y obtener sus certificados'
        ));

//      Rol Profesor -------------------------------
        $profesor = Role::create(array(
            'name' => 'profesor',
            'display_name' => 'Profesor',
            'description' => 'Rol que posee solo permisos para gestionar notas y obtener listados de alumnos'
        ));



        $permisos = Permission::all();
        foreach($permisos as $permiso) {
            if (($permiso->name == 'ver_lista_cursos') || ($permiso->name == 'crear_cursos') || ($permiso->name == 'editar_cursos') || ($permiso->name == 'eliminar_cursos') || ($permiso->name == 'ver_notas_profe') || ($permiso->name == 'agregar_notas') || ($permiso->name == 'editar_notas') || ($permiso->name == 'eliminar_notas') || ($permiso->name == 'listar_alumnos') || ($permiso->name == 'eliminar_part_curso') || ($permiso->name == 'agregar_part_curso')) {
                $coord->attachPermission($permiso);
            }

            if (($permiso->name == 'ver_notas_profe') || ($permiso->name == 'agregar_notas') || ($permiso->name == 'editar_notas') || ($permiso->name == 'eliminar_notas') || ($permiso->name == 'listar_alumnos')) {
                $profesor->attachPermission($permiso);
            }
            if (($permiso->name == 'obtener_certificado') || ($permiso->name == 'ver_perfil_part') || ($permiso->name == 'editar_perfil_part') || ($permiso->name == 'ver_notas_part') || ($permiso->name == 'ver_cursos_part')) {
                $participante->attachPermission($permiso);
            }

            $admin->attachPermission($permiso);
        }

    }

}