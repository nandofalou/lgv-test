<?php

namespace App\Models;

use CodeIgniter\Model;

class CityModel extends Model {

    protected $table = 'city';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = ['id', 'state_id', 'name', 'ibge'];

}
