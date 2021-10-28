<?php

//passei por aqui

namespace App\Libraries;

use App\Libraries\LibLoginAuth;

class LibApiAuth {

    private $db;
    private $tokenVar;
    static $app;
    static $contentType;

    public function __construct() {
        $this->tokenVar = 'token';
        $this->db = \Config\Database::connect();
    }

    /**
     * Autentica o APP
     * @param type $authkey
     * @param type $authtoken
     * @return Object
     */
    public function getAppAuth($authkey, $authtoken) {

        $builder = $this->db->table('app');
        $builder->select('
            app.id as app_id
            , app.name as app_name'
        );
        $builder->where([
            'app.key' => $authkey,
            'app.token' => $authtoken,
        ]);
        self::$app = $builder->get()->getRow();

        return self::$app;
    }

    /**
     * Retorna autenticação do app e do usuário vindos do header
     * @param type $request
     * @return type
     */
    public function getAuth($request) {
        $dados = (object) [
                    'PHP_AUTH_USER' => $request->getServer('PHP_AUTH_USER'), 'PHP_AUTH_PW' => $request->getServer('PHP_AUTH_PW'), 'PHP_AUTH_DIGEST' => $request->getServer('PHP_AUTH_DIGEST'), 'HTTP_AUTHORIZATION' => $request->getServer('HTTP_AUTHORIZATION')
        ];

        //for non apache server
        if (!empty($dados->HTTP_AUTHORIZATION)) {
            list($dados->PHP_AUTH_USER, $dados->PHP_AUTH_PW) = explode(':', base64_decode(substr($dados->HTTP_AUTHORIZATION, 6)));
        }

        return (object) [
                    'appkey' => $dados->PHP_AUTH_USER,
                    'apptoken' => $dados->PHP_AUTH_PW,
                    'usertoken' => $request->getServer('HTTP_X_USER_TOKEN'),
        ];
    }

}
