<?php
namespace ShahiemSeymor\Roles\Tests;

use PluginTestCase;

class ExampleTest extends PluginTestCase
{
    /**
     * Example test for loading database in memory (sqlite)
     *
     * The parent class PluginTestCase is included in OctoberCMS^ and will be available when the plugin is installed
     * in the plugins directory of October. The base class calls a createApplication method that builds up the database
     * in memory using the sqlite driver. If this test passes in the October context, then the database was built
     * successfully.
     *
     * ^You must have updated OctoberCMS to the point that the PluginTestCase is in the /tests directory and that the
     * composer.json loads it in autoload-dev.
     */
    public function test_loading_database_in_memory(){
        $this->assertTrue(true);
    }
}