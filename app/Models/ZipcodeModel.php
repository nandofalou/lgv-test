<?php

namespace App\Models;

use CodeIgniter\Model;

class ZipcodeModel extends Model {

    protected $table = 'zipcode';
    protected $primaryKey = 'zipcode';
    protected $returnType = 'object';
    protected $allowedFields = ['zipcode', 'street', 'complement', 'district', 'city_id'];

}
