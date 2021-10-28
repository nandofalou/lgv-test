<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\LibLocation;
use CodeIgniter\API\ResponseTrait;

class Users extends BaseResourceController {

    use ResponseTrait;

    private $zip;
    private $erros;
    private $userData;

    public function __construct() {
        $this->model = new UserModel();
        $this->zip = new LibLocation();
        $this->erros = [];
        $this->genUserData();
    }

    public function index() {
        $query = [];
        if ($this->request->getGet('name')) {
            $query['name'] = $this->request->getGet('name');
        }

        if ($this->request->getGet('cpf')) {
            $query['cpf'] = $this->request->getGet('cpf');
        }
        // $lista = $this->model->findAll();
        $lista = $this->model->search($query);
        if (!empty($lista)) {
            return $this->respondCreated($this->getTemplate('users', $lista, $query), 200);
        }

        return $this->respondNoContent();
    }

    public function view($id) {
        // $lista = $this->model->find($id);
        $lista = $this->model->search(['id' => $id]);
        if (!empty($lista)) {

            return $this->respondCreated($this->getTemplate('users', $lista), 200);
        }

        return $this->respondNoContent();
    }

    public function create() {
        $inputData = (object) $this->getInput();
        $data = $this->validateUserData($inputData);

        if (empty($this->erros)) {
            if (property_exists($this->userData, 'email')) {
                if ($this->emailExists($this->userData->email)) {
                    $this->erros[] = 'email already exists ';
                }
            } else {
                $this->apiResponse->setError('Invalid email');
            }
        }

        if (empty($this->erros)) {
            $id = $this->model->insert($this->userData);
            if (!empty($id)) {
                $lista = $this->model->search(['id' => $id]);
                return $this->respondCreated($this->getTemplate('users', $lista), 200);
            } else {
                $this->erros[] = 'An error occurred when registering user';
            }
        }

        return $this->fail($this->erros, 400);
    }

    public function save($id) {
        $inputData = !empty($this->getInput()) ? (object) $this->getInput() : (object) $this->request->getRawInput();
        $user = $this->model->find($id);

        if (empty($user)) {
            $this->erros[] = 'ID not found';
        } else {
            $data = $this->validateUserData($inputData);
        }

        if (empty($this->erros)) {

            $this->model->update($id, $this->userData);
            if (!empty($id)) {
                $lista = $this->model->search(['id' => $id]);
                return $this->respondCreated($this->getTemplate('users', $lista), 200);
            } else {
                $this->erros[] = 'An error occurred while updating user';
            }
        }

        return $this->fail($this->erros, 400);
    }

    public function remove($id) {
        $user = $this->model->find($id);
        if (empty($user)) {
            $this->erros[] = 'ID not found';
        } else {
            if ($this->model->delete($id)) {
                $data = (object) [
                            'id' => $id
                ];
                return $this->respondDeleted($this->getTemplate('users', $data), 200);
            } else {
                $this->erros[] = 'An error occurred while deleting the user';
            }
        }
        return $this->fail($this->erros, 400);
    }

    private function validateUserData($userData) {

        if (!is_object($userData)) {
            $this->erros[] = 'Invalid Parameters';
        }

        $this->validateField($userData, 'name');
        $this->validateField($userData, 'email');
        $this->validateField($userData, 'birthdate');
        $this->validateField($userData, 'zipcode');
        $this->validateField($userData, 'phone');
        $this->validateField($userData, 'birthdate');
        $this->validateField($userData, 'cpf');

        if (empty($this->erros)) {
            if (property_exists($userData, 'zipcode')) {

                $zip = $userData->zipcode;
                $rs = $this->zip->findZipcode($zip);
                if (empty($rs)) {
                    $this->erros[] = 'Invalid Zipcode ' . $zip;
                } else {
                    $this->validateField($userData, 'zipcode');
                }
            } else {
                $this->apiResponse->setError('Invalid zipcode');
            }
        }
    }

    private function validateField($userData, $field) {
        if (empty($this->erros)) {
            if (property_exists($userData, $field) && !empty($userData->$field)) {
                if (in_array($field, ['zipcode', 'cpf', 'phone'])) {
                    $userData->$field = preg_replace("/\D/", '', $userData->$field);
                }
                $this->userData->$field = trim($userData->$field);
            } else {
                $this->erros[] = 'Invalid user ' . $field;
            }
        }
    }

    private function emailExists($email) {
        $rs = $this->model->where('email', $email)->first();
        if (!empty($rs)) {
            return true;
        }
        return false;
    }

    private function genUserData() {
        $this->userData = (object) [
                    'name' => null,
                    'email' => null,
                    'birthdate' => null,
                    'cpf' => null,
                    'phone' => null,
                    'zipcode' => null,
        ];
    }

}
