<?php 
/**
 * Created by ShahiemSeymor.
 * Date: 6/26/14
 */
namespace ShahiemSeymor\Roles\Models;
use RainLab\User\Models\User;
use Model;

class UserPermission extends Model
{
	use \October\Rain\Database\Traits\Validation;
	
    protected $table = 'shahiemseymor_permissions';

    public $rules = [
        'name'                  => 'required|unique:shahiemseymor_permissions',
    ];

 	public $belongsToMany = [
        'groups' => ['ShahiemSeymor\Roles\Models\UserGroup', 'table' => 'shahiemseymor_permission_role']
    ];
}