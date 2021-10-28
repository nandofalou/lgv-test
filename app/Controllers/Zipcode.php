<?php

namespace App\Controllers;

use App\Libraries\LibLocation;
use CodeIgniter\API\ResponseTrait;

class Zipcode extends BaseResourceController {

    use ResponseTrait;

    private $zip;

    public function __construct() {
        $this->zip = new LibLocation();
    }

    public function view($zip) {
        $lista = $this->zip->findZipcode($zip);

        if (!empty($lista)) {
            return $this->respondCreated($this->getTemplate('zipcode', $lista, ['zipcode' => $zip]), 200);
        }
        return $this->respondNoContent();
    }

    

}
