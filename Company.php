<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
use HasFactory;
    protected $table = 'companies';

    protected $fillable = [
        'name',
        'subdomain',
        'db_database',
        'db_hostname',
        'db_username',
        'db_password'

    ];
}