<?php

namespace App\Models;

use CodeIgniter\Model;

class AppModel extends Model {

    protected $table = 'app';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = [
        'id',
        'name',
        'key',
        'token',
    ];

}
