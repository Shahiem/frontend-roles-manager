<?php namespace ShahiemSeymor\Roles\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use ShahiemSeymor\Roles\Models\UserPermission;
use Flash;

class Permissions extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RainLab.User', 'user', 'perms');
    }

    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) 
        {
            foreach ($checkedIds as $permissionId) {
                if (!$permission = UserPermission::find($permissionId))
                    continue;

                $permission->delete();
            }

            Flash::success('The Permission has been deleted successfully.');
        }

         return $this->listRefresh();
    }
}