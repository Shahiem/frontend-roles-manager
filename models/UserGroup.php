<?php 
namespace ShahiemSeymor\Roles\Models;

use Auth;
use Model;
use RainLab\User\Models\User;
use RainLab\User\Components\Account;

class UserGroup extends Model
{
    
    use \October\Rain\Database\Traits\Validation;
    
    protected $table      = 'shahiemseymor_roles';

    public $rules         = [
        'name'  => 'required|unique:shahiemseymor_roles',
    ];
    
    public $belongsToMany = [
        'users' => ['Rainlab\User\Models\User',                  'table' => 'shahiemseymor_assigned_roles',  'key' => 'role_id'],
        'perms' => ['ShahiemSeymor\Roles\Models\UserPermission', 'table' => 'shahiemseymor_permission_role', 'key' => 'role_id', 'otherKey' => 'permission_id']
    ];

    public static function hasRole($can)
    {
    	$account = new Account;

        if(Auth::check())
        {
    	   $roles = json_decode(User::find($account->user()->id)->groups);

    	    foreach($roles as $role)
    	    {
    	    	if($role->name == $can)
    	    	{
    	    		return true;
    	    	}
    	    }

        }
        
	    return false;
    }

    public static function can($permissions)
    {
    	$account = new Account;
        $permissions = !is_array($permissions) ? [$permissions] : $permissions;

        if(Auth::check())
        {
        	$roles = json_decode(User::find($account->user()->id)->groups);
         	foreach($roles as $role)
    	    {
    	    	foreach(UserGroup::find($role->id)->perms as $perm)
    	    	{
     				if (in_array($perm->name, $permissions))
                    {
                        return true;
                    }
    	    	}
    	    }
        }
    }

    public function newUserAddToDefaultGroup()
    {
        User::created(function($user) {
            $defaultGroups = static::where('default_group', '=', 1)->get();
            $user->groups()->sync($defaultGroups);
        });
    }

    public function afterSave()
    {
        if($this->default_group)
        {
            $this->addAllUsersToGroup();
        }
    }

    public function addAllUsersToGroup()
    {
        $this->users()->sync(User::lists('id'));
    }

}