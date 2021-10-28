<?php

namespace App\Libraries;

use App\Models\StateModel;
use App\Models\CityModel;
use App\Models\ZipcodeModel;

class LibLocation {

    private $db;
    private $state;
    private $city;
    private $zipcode;

    public function __construct() {

        $this->state = new StateModel();
        $this->city = new CityModel();
        $this->zipcode = new ZipcodeModel();
        $this->db = \Config\Database::connect();
    }

    public function findZipcode($zip) {
        $zip = preg_replace("/[^0-9]/", "", $zip);

        return $this->getZip($zip);
    }

    private function getZip($zip) {
        $rzip = $this->getLocationById($zip);

        if (!$rzip) {
            $rzip = $this->getRemoteZip($zip);
        }

        if ($rzip) {
            return $rzip;
        }
        return null;
    }

    private function getRemoteZip($zip) {

        $rs = $this->httpGET($zip);
        if (!empty($rs) && property_exists($rs, 'cep')) {
            $city = (object) [
                        'name' => $rs->localidade,
                        'state' => strtoupper($rs->uf),
                        'ibge' => $rs->ibge,
            ];
            $idCity = $this->getSetCity($city);
            $data = (object) [
                        'street' => $rs->logradouro,
                        'district' => $rs->bairro,
                        'complement' => $rs->complemento,
                        'city_id' => $idCity,
                        'zipcode' => $zip,
            ];
            $zipid = $this->addZip($data);
            $dataZip = $this->getLocationById($zip);
            return $dataZip;
        }
        return false;
    }

    private function addZip($data) {
        $data = array(
            'street' => !empty($data->street) ? $data->street : "Centro"
            , 'district' => !empty($data->district) ? $data->district : "Centro"
            , 'complement' => !empty($data->complement) ? $data->complement : null
            , 'city_id' => $data->city_id
            , 'zipcode' => $data->zipcode
        );
        return $this->zipcode->insert($data);
    }

    private function getSetCity($city) {
        $rs = $this->city->where('ibge', $city->ibge)->first();
        if (!empty($rs)) {
            $city_id = $rs->id;
        } else {
            $state_id = $this->getSetState($city->state);

            $dataCity = [
                'state_id' => $state_id,
                'name' => $city->name,
                'ibge' => $city->ibge,
            ];
            $city_id = $this->city->insert($dataCity);
        }
        return $city_id;
    }

    private function getSetState($state, $statename = null, $country_id = 1) {
        $rs = $this->state->where('uf', $state)->first();

        if (!empty($rs)) {
            $state_id = $rs->id;
        } else {
            $dadosRegion = [
                'name' => $statename ?? $state,
                'uf' => $state
            ];
            $state_id = $this->state->insert($dadosRegion);
        }
        return $state_id;
    }

    public function getLocationById($zipcode) {
        $this->db = \Config\Database::connect();
        $builder = $this->db->table('zipcode');
        $builder->select('
            zipcode.zipcode 
            ,zipcode.street 
            ,zipcode.district 
            ,zipcode.complement 
            ,city.name as city 
            ,city.id as city_id
            ,state.name as state 
            ,state.uf as uf 
            ');
        $builder->join('city', 'city.id = zipcode.city_id', 'LEFT');
        $builder->join('state', 'state.id = city.state_id', 'LEFT');
        $builder->where('zipcode.zipcode', $zipcode);
        return $builder->get()->getRow();
    }

    protected function httpGET($zipcode) {
        $client = \Config\Services::curlrequest([
                    'baseURI' => 'https://viacep.com.br',
                    'timeout' => 20
        ]);
        $headers = [
            'Accept' => 'application/json',
            'User-Agent' => 'PHP8',
            'Accept' => '*/*',
        ];
        $options = ['headers' => $headers];
        $options['http_errors'] = false;
        $options['verify'] = false;
        $options['connect_timeout'] = 0;
        $options['baseURI'] = 'https://viacep.com.br';
        // $options['debug'] = WRITEPATH . 'logs' . '/storage_http.log';
        $methosPath = '/ws/' . $zipcode . '/json/';

        $response = $client->request('GET', $methosPath, $options);

        return $this->content($response);
    }

    /**
     * get response content
     * @param response $request
     * @return mixed
     */
    protected function content($request) {
        $body = '';
        $Encoding = trim($request->getHeaderLine('Content-Encoding'));

        if ($Encoding === 'gzip') {
            $body = gzdecode($request->getBody());
        } else {
            $body = $request->getBody();
        }

        $type = trim($request->getHeaderLine('Content-Type'));
        if (strpos($type, 'json')) {
            $body = json_decode($body);
        } else {
            $body = null;
        }

        return $body;
    }

}
