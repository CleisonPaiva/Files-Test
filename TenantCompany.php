<?php

namespace Tests;

use App\Models\Tenant\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use function env;

abstract class TenantCompany extends TestCase
{
    use RefreshDatabase;


    protected function refreshApplication()
    {
        parent ::refreshApplication();


        Artisan::call('migrate', [
            '--force' => true,
            '--path' => [
                '/database/migrations/',

            ]
        ]);
        $test=Company ::factory() -> create();

        $this -> artisan( "tenants:migrations {$test->id}" );


    }

    protected function assertSystemDatabaseHas( $table, array $data )
    {
        $this -> assertDatabaseHas( $table, $data, env( 'DB_CONNECTION' ) );
    }

    protected function assertSystemDatabaseMissing( $table, array $data )
    {
        $this -> assertDatabaseMissing( $table, $data, env( 'DB_CONNECTION' ) );
    }
}