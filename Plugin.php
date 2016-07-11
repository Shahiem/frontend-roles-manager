<?php
namespace ShahiemSeymor\Roles;

use Backend;
use Event;
use RainLab\User\Models\User;
use ShahiemSeymor\Roles\Models\Group;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    public $require = ['RainLab.User'];

    public function pluginDetails()
    {
        return [
            'name'        => 'Frontend User Roles Manager',
            'description' => 'The plugin lets you manage frontend user roles and permissions.',
            'author'      => 'Shahiem Seymor',
            'homepage'    => 'http://octobercms.com/plugin/shahiemseymor-roles',
        ];
    }

    public function boot()
    {
        User::extend(function ($model) {
            $model->belongsToMany['groups']      = ['ShahiemSeymor\Roles\Models\Group', 'table' => 'shahiemseymor_assigned_roles', 'otherKey' => 'role_id'];
            $model->belongsToMany['permissions'] = ['ShahiemSeymor\Roles\Models\Group', 'table' => 'shahiemseymor_assigned_roles', 'otherKey' => 'role_id'];
        });

        $userGroup = new Group;
        $userGroup->newUserAddToDefaultGroup();

        Event::listen('backend.menu.extendItems', function ($manager) {
            $manager->addSideMenuItems('RainLab.User', 'user', [
                'roles' => [
                    'label' => trans('shahiemseymor.roles::lang.roles'),
                    'icon'  => 'icon-users',
                    'code'  => 'roles',
                    'owner' => 'RainLab.User',
                    'url'   => Backend::url('shahiemseymor/roles/groups'),
                ],
                'perms' => [
                    'label' => trans('shahiemseymor.roles::lang.permissions'),
                    'icon'  => 'icon-key',
                    'code'  => 'perms',
                    'owner' => 'RainLab.User',
                    'url'   => Backend::url('shahiemseymor/roles/permissions'),
                ],
            ]);
        });

        Event::listen('backend.form.extendFields', function ($widget) {
            if (!$widget->getController() instanceof \RainLab\User\Controllers\Users) {
                return;
            }

            if (!$widget->model instanceof \RainLab\User\Models\User) {
                return;
            }

            $widget->addFields([
                'groups' => [
                    'label'        => trans('shahiemseymor.roles::lang.field.groups'),
                    'commentAbove' => trans('shahiemseymor.roles::lang.field.helper.groups'),
                    'tab'          => trans('shahiemseymor.roles::lang.roles'),
                    'type'         => 'relation',
                ],
            ], 'primary');
        });
    }

    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'can'     => function ($can) {return Group::can($can);},
                'hasRole' => function ($role, $user = null) {return Group::hasRole($role, $user);},
            ],
        ];
    }

}
