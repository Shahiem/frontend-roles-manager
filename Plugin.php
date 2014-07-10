<?php
/**
 * Created by ShahiemSeymor.
 * Date: 6/26/14
 */

namespace ShahiemSeymor\Roles;

use App;
use Backend;
use RainLab\User\Models\User;
use RainLab\User\Components\Account;
use ShahiemSeymor\Roles\Models\Custom;
use ShahiemSeymor\Roles\Models\UserGroup;
use System\Classes\PluginBase;
use Illuminate\Foundation\AliasLoader;
use BackendMenu;
use Event;

class Plugin extends PluginBase
{
    public $require = ['RainLab.User'];
    public function pluginDetails()
    {
        return [
            'name' => 'Frontend Roles Manager',
            'description' => 'The plugin lets you manage frontend user roles and permissions.',
            'author' => 'ShahiemSeymor',
        ];
    }

    public function boot()
    {
        User::extend(function($model) {
            $model->belongsToMany['groups'] = ['ShahiemSeymor\Roles\Models\UserGroup', 'table' => 'shahiemseymor_assigned_roles', 'foreignKey' => 'role_id'];
        });  

        Event::listen('backend.form.extendFields', function($widget) 
        {
            if (!$widget->getController() instanceof \RainLab\User\Controllers\Users) return;
            if (!$widget->model instanceof \RainLab\User\Models\User) return;
            
            $widget->addFields([
                'groups' => [
                    'label'   => 'Roles',
                    'tab'     => 'Permissions',
                    'type' => 'relation',
                ],
            ], 'primary');
        });
   }

    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'can' => function($can) { return UserGroup::can($can); },
                'hasRole' => function($can) { return UserGroup::hasRole($can); }
            ]
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Roles & Permissions',
                'description' => 'Manage user roles and permissions.',
                'category'    => 'Users',
                'url'         => Backend::url('shahiemseymor/roles/groups'),
                'icon' => 'icon-key'
            ]
        ];
    }

}
