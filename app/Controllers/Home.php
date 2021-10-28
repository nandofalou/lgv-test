<?php

namespace App\Controllers;

use App\Models\AppModel;

class Home extends BaseController {

    public function index() {
        $migrate = \Config\Services::migrations();
        $ar = $migrate->findMigrations();
        try {
            $migrate->latest();
        } catch (\Throwable $e) {
            // error
        }
        return view('welcome_message');
    }

}
