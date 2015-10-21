<?php
namespace ShahiemSeymor\Roles;

use Backend;
use Event;
use RainLab\User\Models\UserGroup as RainLabUserGroup;
use ShahiemSeymor\Roles\Models\UserGroup;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    public $require = ['RainLab.User'];

    public function pluginDetails()
    {
        return [
            'name' => 'Frontend User Roles Manager',
            'description' => 'The plugin lets you manage frontend user roles and permissions.',
            'author' => 'Shahiem Seymor',
            'homepage' => 'http://octobercms.com/plugin/shahiemseymor-roles',
        ];
    }

    public function boot()
    {
        RainLabUserGroup::extend(function ($model) {
            $model->belongsToMany['perms'] = ['ShahiemSeymor\Roles\Models\Permission', 'table' => 'shahiemseymor_permission_role', 'key' => 'role_id', 'otherKey' => 'permission_id'];
        });

        Event::listen('backend.menu.extendItems', function ($manager) {
            $manager->addSideMenuItems('RainLab.User', 'user', [
                'perms' => [
                    'label' => 'Permissions',
                    'icon' => 'icon-key',
                    'code' => 'perms',
                    'owner' => 'RainLab.User',
                    'url' => Backend::url('shahiemseymor/roles/permissions'),
                ],
            ]);
        });

        Event::listen('backend.form.extendFields', function ($widget) {
            if (!$widget->getController() instanceof \RainLab\User\Controllers\UserGroups) {
                return;
            }

            if (!$widget->model instanceof \RainLab\User\Models\UserGroup) {
                return;
            }

            $widget->addFields([
                'perms' => [
                    'label' => 'Permissions',
                    'commentAbove' => 'Specify which groups this person belongs to.',
                    'span' => 'right',
                    'type' => 'relation',
                    'emptyOption' => 'There are no permissions, you should create one first!',
                ],
            ], 'primary');
        });
    }

    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'can' => function ($can) {return UserGroup::can($can);},
                'hasRole' => function ($role, $user = null) {return UserGroup::hasRole($role, $user);},
            ],
        ];
    }

}
