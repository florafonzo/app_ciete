<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword, EntrustUserTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre', 'apellido', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function participante(){
		return $this->hasOne('App\Models\Participante','id');
	}

    public function profesor(){
        return $this->hasOne('App\Models\Profesor','id');
    }

    public function checkUser($id) {

        $result = \DB::select('select users.id, permissions.name
                                from users, roles, role_user, permissions, permission_role
                                where users.id = ? and 
                                users.id=role_user.user_id and 
                                role_user.role_id=roles.id and 
                                roles.id=permission_role.role_id and 
                                permission_role.permission_id=permissions.id and 
                                permissions.name= ?', array($id,'eliminar_usuarios')); 

        
        //$user = $this->where('id','=', $id)->first();
        $cont= $this->count();
        //dd($cont);

        if( $cont == 1 ){
        	if(($result->id == $id) && ($result->name =="eliminar_usuarios")){
        		return true;
        	}
        }else{
        	return false;
        }

        
        /*foreach ($result as $value) {
            $idd= $value->id;
            $permiso=$value->name;
            
            if($idd==$id && $permiso=="eliminar_usuarios" && $cont==1 )
            {
                return true;
            }
		
        }*/
    }
}
