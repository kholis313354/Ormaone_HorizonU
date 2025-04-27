<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'level',
        'name',
        'email',
        'password',
    ];

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}