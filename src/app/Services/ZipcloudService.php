<?php

namespace App\Services;

use GuzzleHttp\Client;

class ZipcloudService
{
    public function getAddress(string $zipCode)
    {
        $client = new Client();

        $url = 'https://zipcloud.ibsnet.co.jp/api/search';
        $option = [
          'headers' => [
            'Accept' => '*/*',
            'Content-Type' => 'application/x-www-form-urlencoded'
          ],
          'form_params' => [
            'zipcode' => $zipCode
          ]
        ];

        $response = $client->request('POST', $url, $option);

        $result = json_decode($response->getBody()->getContents(), true);

        $args = [
            'prefCode'  => is_null($result['results']) ? null : $result['results'][0]['prefcode'],
            'city'      => is_null($result['results']) ? null : $result['results'][0]['address2'] . $result['results'][0]['address3']
        ];

        return $args;
    }
}
