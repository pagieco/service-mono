<?php

namespace App\Http;

use App\Exceptions\CurlRequestException;

class Curl
{
    /**
     * Execute a curl get request.
     *
     * @param  string $url
     * @return bool|string
     * @throws \Throwable
     */
    public static function get(string $url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);

        $response = curl_exec($curl);
        $statusCode = curl_getinfo($curl)['http_code'];

        throw_if($statusCode >= 400, new CurlRequestException(Response::$statusTexts[$statusCode]));
        throw_if($response === false, new CurlRequestException(curl_error($curl)));

        curl_close($curl);

        return $response;
    }

    /**
     * Execute a curl post request.
     *
     * @param  string $url
     * @param  array $payload
     * @return bool|string
     * @throws \Throwable
     */
    public static function post(string $url, array $payload)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_CUSTOMREQUEST => 'POST',
        ]);

        $response = curl_exec($curl);
        $statusCode = curl_getinfo($curl)['http_code'];

        throw_if($statusCode >= 400, new CurlRequestException(Response::$statusTexts[$statusCode]));
        throw_if($response === false, new CurlRequestException(curl_error($curl)));

        curl_close($curl);

        return $response;
    }
}
