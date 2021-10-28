<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = [
        'id',
        'name',
        'email',
        'birthdate',
        'cpf',
        'phone',
        'zipcode',
        'created_on',
        'updated_on',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_on';
    protected $updatedField = 'updated_on';

    public function search(array $query = []) {
        $builder = $this->db->table('users');
        //  (logradouro, bairro, cidade, estado, cep) 
        $builder->select('
            users.id,
            users.name,
            users.email,
            users.birthdate,
            users.cpf,
            users.phone,
            zipcode.zipcode,
            zipcode.street,
            zipcode.complement,
            zipcode.district,
            city.name as city,
            state.name as state,
            users.created_on,
            users.updated_on,
           '
        );

        $builder->join('zipcode', 'users.zipcode = zipcode.zipcode', 'LEFT');
        $builder->join('city', 'city.id = zipcode.city_id', 'LEFT');
        $builder->join('state', 'state.id = city.state_id', 'LEFT');

        if (array_key_exists('id', $query)) {
            $builder->where('users.id', (int) $query['id']);
        }

        if (array_key_exists('name', $query)) {
            $builder->like('users.name', trim($query['name']));
        }

        if (array_key_exists('cpf', $query)) {
            $builder->where('users.cpf', preg_replace("/\D/", '', $query['cpf']));
        }

        return $builder->get()->getResultObject();
    }

}
