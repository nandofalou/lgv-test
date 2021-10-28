<?php

namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Libraries\LibApiAuth;

class BaseResourceController extends ResourceController {

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['url'];
    private $inputJson;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger) {

        parent::initController($request, $response, $logger);
        $this->inputJson = $this->request->getJSON(true);
    }

    /**
     * Get input vaiables
     * @param type $name
     * @return type
     */
    protected function getInput($name = null) {
        if (LibApiAuth::$contentType == 'application/json') {
            $vars = $this->inputJson;
        } else {
            $vars = $this->request->getPost();
        }
        
        if (!empty($name)) {
            if (array_key_exists($name, $vars)) {
                return $vars[$name];
            } else {
                return null;
            }
        } else {
            return $vars;
        }
    }

    protected function getTemplate($name, $data, $query = null) {
        $data = (object) [
                    "status" => 200,
                    "error" => null,
                    $name => $data,
        ];

        if (!empty($query)) {
            $data->query = (object) $query;
        }

        return $data;
    }

}
