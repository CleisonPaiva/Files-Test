<?php

namespace Tests;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use function env;

abstract class  TenantAwareTestCase extends TestCase
{
    use RefreshDatabase;

    protected function refreshApplication()
    {
        parent::refreshApplication();


       Artisan::call('migrate', [
            '--force' => true,
            '--path' => [
                '/database/migrations/',

            ]
        ]);

        Artisan::call('db:seed', [
            '--class' => DatabaseSeeder::class,
        ]);
    }

    protected function assertSystemDatabaseHas($table, array $data)
    {
        $this->assertDatabaseHas($table, $data, env('DB_CONNECTION'));
    }

    protected function assertSystemDatabaseMissing($table, array $data)
    {
        $this->assertDatabaseMissing($table, $data, env('DB_CONNECTION'));
    }
}