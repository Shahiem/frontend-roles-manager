<?php namespace ShahiemSeymor\Roles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateDates extends Migration
{

    public function up()
    {
        Schema::table('shahiemseymor_assigned_roles', function($table)
        {
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shahiemseymor_assigned_roles');
    }

}
