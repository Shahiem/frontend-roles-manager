<?php namespace ShahiemSeymor\Roles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTable extends Migration
{

    public function up()
    {
        Schema::create('shahiemseymor_roles', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('shahiemseymor_assigned_roles', function($table)
        {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
        });

        Schema::create('shahiemseymor_permissions', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->timestamps();
        });

        Schema::create('shahiemseymor_permission_role', function($table)
        {
            $table->increments('id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();
        });

        Schema::table('users', function($table)
        {
            $table->integer('primary_usergroup');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shahiemseymor_permission_role');
        Schema::dropIfExists('shahiemseymor_permissions');

        if(Schema::hasColumn('users', 'primary_usergroup'))
        {
            Schema::table('users', function($table)
            {
                $table->dropColumn('primary_usergroup');
            });
        }
    }

}
