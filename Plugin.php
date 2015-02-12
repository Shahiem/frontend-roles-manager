<?php
namespace ShahiemSeymor\Roles;

use App;
use Event;
use Backend;
use BackendMenu;
use Illuminate\Foundation\AliasLoader;
use RainLab\User\Models\User;
use RainLab\User\Components\Account;
use ShahiemSeymor\Roles\Models\Custom;
use ShahiemSeymor\Roles\Models\UserGroup;
use ShahiemSeymor\Roles\Models\UserAssignedGroup;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    public $require = ['RainLab.User'];

    public function pluginDetails()
    {
        return [
            'name'        => 'Frontend User Roles Manager',
            'description' => 'The plugin lets you manage frontend user roles and permissions.',
            'author'      => 'ShahiemSeymor'
        ];
    }

    public function boot()
    {
        User::extend(function($model) 
        {
            $model->belongsToMany['groups']      = ['ShahiemSeymor\Roles\Models\UserGroup', 'table' => 'shahiemseymor_assigned_roles', 'foreignKey' => 'role_id'];
            $model->belongsToMany['permissions'] = ['ShahiemSeymor\Roles\Models\UserGroup', 'table' => 'shahiemseymor_assigned_roles', 'foreignKey' => 'role_id'];     
        });  

        $userGroup = new UserGroup;
        $userGroup->newUserAddToDefaultGroup();

        Event::listen('backend.menu.extendItems', function($manager)
        {
           $manager->addSideMenuItems('RainLab.User', 'user', [
                'roles' => [
                    'label'       => 'Roles',
                    'icon'        => 'icon-users',
                    'code'        => 'roles',
                    'owner'       => 'RainLab.User',
                    'url'         => Backend::url('shahiemseymor/roles/groups')
                ],
                'perms' => [
                    'label'       => 'Permissions',
                    'icon'        => 'icon-key',
                    'code'        => 'perms',
                    'owner'       => 'RainLab.User',
                    'url'         => Backend::url('shahiemseymor/roles/permissions')
                ]
            ]);
        });

        Event::listen('backend.form.extendFields', function($widget) 
        {
            if (!$widget->getController() instanceof \RainLab\User\Controllers\Users) return;
            if (!$widget->model instanceof \RainLab\User\Models\User) return;
            
             $widget->addFields([
                'groups'       => [
                    'label'    => 'Roles',
                    'tab'      => 'Groups',
                    'type'     => 'relation'
                ]
            ], 'primary');
        });
    }

    public function registerMarkupTags()
    {
        return [
            'functions'   => [
                'can'     => function($can) { return UserGroup::can($can); },
                'hasRole' => function($can) { return UserGroup::hasRole($can); }
            ]
        ];
    }

}
