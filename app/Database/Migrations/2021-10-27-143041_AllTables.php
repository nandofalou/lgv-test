<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AllTables extends Migration {

    private $driver;

    public function up() {
        $this->driver = $this->db->DBDriver;
        $this->createState();
        $this->createCity();
        $this->createZipcode();
        $this->createUser();
        $this->createApp();
    }

    public function down() {
        $this->forge->dropTable('app', true);
        $this->forge->dropTable('users', true);
        $this->forge->dropTable('zipcode', true);
        $this->forge->dropTable('city', true);
        $this->forge->dropTable('state', true);
    }

    public function createState() {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '50',
            ],
            'uf' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '5',
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('name', false, false);
        $this->forge->addKey('abbr', false, false);

        $this->create('state', true, ['ENGINE' => 'InnoDB']);

        $data = [
            ["1", "1", "Acre", "AC"],
            ["2", "1", "Alagoas", "AL"],
            ["3", "1", "Amapá", "AP"],
            ["4", "1", "Amazonas", "AM"],
            ["5", "1", "Bahia", "BA"],
            ["6", "1", "Ceará", "CE"],
            ["7", "1", "Distrito Federal", "DF"],
            ["8", "1", "Espírito Santo", "ES"],
            ["9", "1", "Goiás", "GO"],
            ["10", "1", "Maranhão", "MA"],
            ["11", "1", "Mato Grosso", "MT"],
            ["12", "1", "Mato Grosso do Sul", "MS"],
            ["13", "1", "Minas Gerais", "MG"],
            ["14", "1", "Pará", "PA"],
            ["15", "1", "Paraíba", "PB"],
            ["16", "1", "Paraná", "PR"],
            ["17", "1", "Pernambuco", "PE"],
            ["18", "1", "Piauí", "PI"],
            ["19", "1", "Rio de Janeiro", "RJ"],
            ["20", "1", "Rio Grande do Norte", "RN"],
            ["21", "1", "Rio Grande do Sul", "RS"],
            ["22", "1", "Rondônia", "RO"],
            ["23", "1", "Roraima", "RR"],
            ["24", "1", "Santa Catarina", "SC"],
            ["25", "1", "São Paulo", "SP"],
            ["26", "1", "Sergipe", "SE"],
            ["27", "1", "Tocantins", "TO"]
        ];


        foreach ($data as $v) {
            $this->db->table('state')->insert([
                "id" => $v[0],
                "name" => $v[2],
                "uf" => $v[3],
            ]);
        }
    }

    public function createCity() {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'auto_increment' => true,
            ],
            'state_id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '50',
            ],
            'ibge' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '30',
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('state_id', false, false);
        $this->forge->addKey('name', false, false);
        $this->forge->addKey('ibge', false, false);
        $this->forge->addUniqueKey(['name', 'state_id']);
        $this->forge->addForeignKey('state_id', 'state', 'id', 'NO ACTION', 'NO ACTION');
        $existTable = $this->db->tableExists('city');
        if (!$existTable) {
            $this->create('city', true, ['ENGINE' => 'InnoDB']);
        }
    }

    public function createZipcode() {
        $this->forge->addField([
            'zipcode' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '20',
            ],
            'city_id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'street' => [
                'type' => 'VARCHAR',
                'null' => true,
                'default' => null,
                'constraint' => '200',
            ],
            'complement' => [
                'type' => 'VARCHAR',
                'null' => true,
                'default' => null,
                'constraint' => '200',
            ],
            'district' => [
                'type' => 'VARCHAR',
                'null' => true,
                'default' => null,
                'constraint' => '50',
            ],
        ]);
        $this->forge->addPrimaryKey('zipcode');
        $this->forge->addKey('street', false, false);
        $this->forge->addKey('district', false, false);
        $this->forge->addKey('city_id', false, false);

        $this->forge->addForeignKey('city_id', 'city', 'id', 'NO ACTION', 'NO ACTION');
        $existTable = $this->db->tableExists('zipcode');
        if (!$existTable) {
            $this->create('zipcode', true, ['ENGINE' => 'InnoDB']);
        }
    }

    private function createUser() {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => 100,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => 100,
            ],
            'birthdate' => [
                'type' => 'DATE',
                'null' => true,
                'default' => null,
            ],
            'cpf' => [
                'type' => 'VARCHAR',
                'null' => true,
                'default' => null,
                'constraint' => 20,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'null' => true,
                'default' => null,
                'constraint' => 20,
            ],
            'zipcode' => [
                'type' => 'VARCHAR',
                'null' => true,
                'default' => null,
                'constraint' => 20,
            ],
            'created_on' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'updated_on' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ]
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->addKey('email', false, false);
        $this->forge->addKey('name', false, false);
        $this->forge->addKey('zipcode', false, false);
        $this->forge->addKey('created_on', false, false);
        $this->forge->addKey('updated_on', false, false);
        $this->forge->addKey('phone', false, false);

        $this->forge->addForeignKey('zipcode', 'zipcode', 'zipcode', 'NO ACTION', 'NO ACTION');


        $existTable = $this->db->tableExists('users');
        if (!$existTable) {
            $this->create('users', true, ['ENGINE' => 'InnoDB']);
        }
    }

    private function createApp() {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '50',
            ],
            'active' => [
                'type' => 'TINYINT',
                'null' => false,
                'constraint' => 1,
                'unsigned' => true,
                'default' => 1,
            ],
            'key' => [
                'type' => 'VARCHAR',
                'null' => false,
                'constraint' => '100',
            ],
            'token' => [
                'type' => 'VARCHAR',
                'null' => true,
                'default' => null,
                'constraint' => '100',
            ]
        ]);
        $this->forge->addPrimaryKey('id');

        $this->forge->addKey('key', false, true);
        $this->forge->addKey('name', false, false);
        $this->forge->addKey('token', false, false);
        $this->forge->addUniqueKey(['key', 'token']);

        $this->create('app', true, ['ENGINE' => 'InnoDB']);

        $data = [
            [
                'id' => '1',
                'name' => 'myApp',
                'key' => 'lojavirtual',
                'token' => 'AAAAC3NzaC1lZDI1NTE5AAAAIAFGivCtTMXmFiPbd5GLHNChKn+MCVrvwjB5GK2APQti'
            ],
        ];

        $this->db->table('app')->insertBatch($data);
    }

    private function create($table, $ifNotExists = true, $attr = array()) {
        if ($this->driver === 'SQLite3') {
            $this->forge->createTable($table, $ifNotExists);
        } else {
            $this->forge->createTable($table, $ifNotExists, ['ENGINE' => 'InnoDB']);
        }
    }

}
