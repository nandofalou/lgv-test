<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\LibApiAuth;
use Config\Services;

class AppAuthFilter implements FilterInterface {

    private $auth;
    private $apiAuth;
    private $app;
    private $error;

    public function before(RequestInterface $request, $arguments = null) {
        $this->error = null;
        $this->apiAuth = new LibApiAuth();
        LibApiAuth::$contentType = $request->getServer('HTTP_CONTENT_TYPE');
        $this->auth = $this->apiAuth->getAuth($request);

        if (!empty($this->auth->appkey)) {
            $this->app = $this->apiAuth->getAppAuth($this->auth->appkey, $this->auth->apptoken);
            if (empty($this->app)) {
                $this->error = 'Invalid APP Token';
            }
        } else {
            $this->error = 'Invalid Basic Auth';
        }

        if ($this->error) {
            return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED, $this->error);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        //
    }

}
