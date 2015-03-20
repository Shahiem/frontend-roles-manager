<?php 
namespace ShahiemSeymor\Roles\Models;

use Model;
use RainLab\User\Models\User;

class Permission extends Model
{

	use \October\Rain\Database\Traits\Validation;
	
    protected $table      = 'shahiemseymor_permissions';

    public $rules         = [
        'name'           => 'required|unique:shahiemseymor_permissions',
    ];

 	public $belongsToMany = [
        'groups'         => ['ShahiemSeymor\Roles\Models\UserGroup', 'table' => 'shahiemseymor_permission_role']
    ];

}