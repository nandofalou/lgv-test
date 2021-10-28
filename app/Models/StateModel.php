<?php

namespace App\Models;

use CodeIgniter\Model;

class StateModel extends Model {

    protected $table = 'state';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = ['id', 'name', 'uf'];

}
