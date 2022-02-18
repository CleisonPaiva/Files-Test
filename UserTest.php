<?php

namespace Tests\Feature\Admin;

use App\Enums\PermissionsEnum;
use App\Models\Admin\User;
use App\Models\Tenant\Company;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\tenantCompany;

class UserTest extends TenantCompany
{


    protected $company;

    public function setUp():void {

        parent::setUp();

        $this->company = Company ::firstOrFail();
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */


    public function test_get_users_unauthenticated()
    {
        $response = $this -> getJson( 'https://' .  $this->company->subdomain . '/api/v1/users' );

        $response -> assertStatus( 401 );
    }

    public function test_get_users_unauthorized()
    {


        $user = User ::factory() -> create();
        $token = $user -> createToken( 'test' ) -> plainTextToken;

        $response = $this
            -> withHeaders( [
                'Authorization' => "Bearer {$token}"
            ] )
            -> getJson( 'https://' .  $this->company->subdomain . '/api/v1/users/datatable' );


        $response -> assertStatus( 403 );
    }

    public function test_it_returns_all_records_when_no_parameters_is_passed()
    {
        $user = User ::factory() -> create();

        $token = $user -> createToken( 'test' ) -> plainTextToken;

        $count=User ::count();

        $user -> assignRole( 'Administrador' );

        $response = $this
            -> withHeaders( [
                'Authorization' => "Bearer {$token}"
            ] )
            -> getJson( 'https://' .  $this->company->subdomain.'/api/v1/users/datatable' )
            -> assertJson( [
                'draw' => 0,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
            ] );

        $response -> assertStatus( 200 );

    }

    public function test_get_fail_user()
    {


        $user = User ::factory() -> create();
        $token = $user -> createToken( 'test' ) -> plainTextToken;

        $user -> assignRole( 'Administrador' );

        $response = $this
            -> withHeaders( [
                'Authorization' => "Bearer {$token}"
            ] )
            -> getJson( 'https://' .  $this->company->subdomain . "/api/v1/users/fake_value" );

        $response -> assertStatus( 404 );
    }

    public function test_get_user()
    {


        $user = User ::factory() -> create();
        $token = $user -> createToken( 'test' ) -> plainTextToken;

        $user -> assignRole( 'Administrador' );

        $response = $this
            -> withHeaders( [
                'Authorization' => "Bearer {$token}"
            ] )
            -> getJson( 'https://' .  $this->company->subdomain . "/api/v1/users/{$user->uuid}" );

        $response -> assertStatus( 200 );
    }


    public function test_validation_404_update_user()
    {



        $user = User ::factory() -> create();
        $token = $user -> createToken( 'test' ) -> plainTextToken;

        $user -> assignRole( 'Administrador' );

        $response = $this
            -> withHeaders( [
                'Authorization' => "Bearer {$token}"
            ] )
            -> postJson( 'https://' .  $this->company->subdomain . '/api/v1/users/update_user/fake_user', [
                'username' => 'teste',
                'firstname' => 'teste',
                'lastname' => 'teste',
                'email' => 'teste.teste@teste.com',
                'password' => '12345678',
                'confirm_password' => '12345678',
                'active' => true,

            ] );

        $response -> assertStatus( 404 );
    }

    public function test_validations_update_user()
    {


        $user = User ::factory() -> create();
        $token = $user -> createToken( 'test' ) -> plainTextToken;

        $user -> assignRole( 'Administrador' );

        $response = $this
            -> withHeaders( [
                'Authorization' => "Bearer {$token}"
            ] )
            -> postJson( 'https://' .  $this->company->subdomain . "/api/v1/users/update_user/{$user->uuid}", [] );


        $response -> assertStatus( 422 );
    }

    public function test_update_user()
    {


        $user = User ::factory() -> create();
        $token = $user -> createToken( 'test' ) -> plainTextToken;


        $user -> assignRole( 'Administrador' );

        $response = $this
            -> withHeaders( [
                'Authorization' => "Bearer {$token}"
            ] )
            -> postJson( 'https://' .  $this->company->subdomain . "/api/v1/users/update_user/{$user->uuid}", [
                'username' => 'teste',
                'firstname' => 'teste',
                'lastname' => 'teste',
                'email' => 'teste.teste@teste.com',
                'password' => '12345678',
                'confirm_password' => '12345678',
                'active' => true,

            ] );

        $response -> assertStatus( 200 );
    }

    public function test_validation_404_delete_user()
    {



        $user = User ::factory() -> create();
        $token = $user -> createToken( 'test' ) -> plainTextToken;

        $user -> assignRole( 'Administrador' );

        $response = $this
            -> withHeaders( [
                'Authorization' => "Bearer {$token}"
            ] )
            -> deleteJson( 'https://' .  $this->company->subdomain . '/api/v1/users/fake_user' );

        $response -> assertStatus( 404 );
    }

    public function test_delete_user()
    {


        $user = User ::factory() -> create();
        $token = $user -> createToken( 'test' ) -> plainTextToken;

        $user -> assignRole( 'Administrador' );

        $response = $this
            -> withHeaders( [
                'Authorization' => "Bearer {$token}"
            ] )
            -> deleteJson( 'https://' .  $this->company->subdomain . "/api/v1/users/{$user->uuid}" );

        $response -> assertStatus( 204 );
    }


    public function test_get_fail_user_is_not_permission()
    {



        $user = User ::factory() -> create();
        $token = $user -> createToken( 'test' ) -> plainTextToken;


        $response = $this
            -> withHeaders( [
                'Authorization' => "Bearer {$token}"
            ] )
            -> getJson( 'https://' .  $this->company->subdomain . "/api/v1/users/fake_value" );

        $response -> assertStatus( 403 );
    }

    public function test_get_user_is_not_permission()
    {


        $user = User ::factory() -> create();
        $token = $user -> createToken( 'test' ) -> plainTextToken;

        $response = $this
            -> withHeaders( [
                'Authorization' => "Bearer {$token}"
            ] )
            -> getJson( 'https://' .  $this->company->subdomain . "/api/v1/users/{$user->uuid}" );

        $response -> assertStatus( 403 );
    }


    public function test_update_user_is_not_permission()
    {



        $user = User ::factory() -> create();
        $token = $user -> createToken( 'test' ) -> plainTextToken;


        $response = $this
            -> withHeaders( [
                'Authorization' => "Bearer {$token}"
            ] )
            -> postJson( 'https://' .  $this->company->subdomain . "/api/v1/users/update_user/{$user->uuid}", [
                'username' => 'teste',
                'firstname' => 'teste',
                'lastname' => 'teste',
                'email' => 'teste.teste@teste.com',
                'password' => '12345678',
                'confirm_password' => '12345678',
                'active' => true,

            ] );

        $response -> assertStatus( 403 );
    }


    public function test_delete_user_is_not_permission()
    {

        $user = User ::factory() -> create();
        $token = $user -> createToken( 'test' ) -> plainTextToken;

        $response = $this
            -> withHeaders( [
                'Authorization' => "Bearer {$token}"
            ] )
            -> deleteJson( 'https://' .  $this->company->subdomain . "/api/v1/users/{$user->uuid}" );

        $response -> assertStatus( 403 );
    }
}