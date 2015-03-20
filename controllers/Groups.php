<?php namespace ShahiemSeymor\Roles\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Flash;
use ShahiemSeymor\Roles\Models\Group;

class Groups extends Controller
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
        BackendMenu::setContext('RainLab.User', 'user', 'roles');
    }

    public function index_onDelete()
    {
        if(($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) 
        {
            foreach ($checkedIds as $roleId) 
            {
                if (!$role = Group::find($roleId))
                    continue;

                $role->delete();
            }

            Flash::success('The role has been deleted successfully.');
        }

        return $this->listRefresh();
    }
    
}