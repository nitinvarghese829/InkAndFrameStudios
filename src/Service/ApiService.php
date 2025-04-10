<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;


class ApiService
{
    static function get($uri, $headers = null, $query = null)
    {
        $client = new Client();

        $response = $client->get($uri, [
            'headers' => $headers,
            'query' => $query,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    static function post($url, $data, $headers)
    {
        $client = new Client();
        $options = [
            'headers' => $headers,
            'body'    => $data,
        ];

        $promise = $client->postAsync($url, $options);
        $response = $promise->wait();

        try {
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }


    static function fetchAsyncData($dataUrls)
    {
        $client = new Client();
        $promises = [];
        foreach ($dataUrls as $key => $dataUrl) {
            $promises[$key] = $client->getAsync($dataUrl);
        }

        // Wait for all promises to complete
        Promise\Utils::settle($promises)->wait();

        $responses = [];
        // Process the results
        foreach ($promises as $key => $promise) {
            // Access the response from the promise
            $response = $promise->wait();

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            // Check for errors or process the response
            if ($statusCode == 200) {
                $responses[$key] = json_decode($body, true);;
            }
        }
        return $responses;
    }
}
