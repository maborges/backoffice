<?php

namespace App\Services;

/**
 * Classe Helper - Classe auxiliar responsável por prover interface como Sankhya..
 *
 * @author Borgus
 * @copyright Copyright (c) 2024, BORGUS Software
 */
class Sankhya
{

    const TOKEN_EXPIRY_SECONDS = 1740; // 29 minutos (pode ser ajustado conforme INATSESSTIMEOUT)


    static $urlApiQuery        = 'https://api.sankhya.com.br/gateway/v1/mge/service.sbr?serviceName=DbExplorerSP.executeQuery&outputType=json';

    static $serviceNameQuery   = 'DbExplorerSP.executeQuery';
    static $serviceNameUpdate  = 'DatasetSP.save';

    static $contentType = 'Content-Type: application/';
    static $Bearer      = 'Authorization: Bearer ';



    public static function bearerToken()
    {
        /*
        // Verifica se o token existe e não expirou
        if (isset($_SESSION['bearer_token']) && isset($_SESSION['token_expiry']) && $_SESSION['token_expiry'] > time()) 
        {
            return  array(
                "rows" => $_SESSION['bearer_token'],
                "errorCode" => 0,
                "errorMessage" => "ok"
            );
        }
        */

        // Token expirado ou inexistente, faz login
        return Sankhya::login();
    }

    public static function login(): array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.sankhya.com.br/login',
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "token: 1569e9fb-e884-470b-9ba3-1cb34f4ff5bd",
                "appkey: 665cd3ae-b474-4515-8bcf-920bbd6984e3",
                "username: marcos.borges@borgus.com.br",
                "password: &Xpert01"
            ]
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);


        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);

            if (!empty($data['bearerToken'])) {

                if (session_status() === PHP_SESSION_NONE) {
                    // Se não estiver iniciada, inicia a sessão
                    session_start();
                }
                
                // Armazena o token e o tempo de expiração na sessão
                $_SESSION['bearer_token'] = $data['bearerToken'];
                $_SESSION['token_expiry'] = time() + Sankhya::TOKEN_EXPIRY_SECONDS;

                return  array(
                    "rows" => $data['bearerToken'],
                    "errorCode" => 0,
                    "errorMessage" => "ok"
                );                
                
            }
        }

        return  array(
            "rows" => $response,
            "errorCode" => 1,
            "errorMessage" => 'Falha ao autenticar do Sankhya: ' . ($response ?: 'Sem resposta')
        );

    }


    public static function queryExecuteAPI($sqlExecute): array
    {
        $tokenSankhya = Sankhya::bearerToken();

        // Verifica se a API executou
        if ($tokenSankhya['errorCode']) {
            return array(
                "rows" => $tokenSankhya['rows'],
                "errorCode" => $tokenSankhya['errorCode'] ?? 1,
                "errorMessage" => $tokenSankhya['errorMessage']
            );
        }

        $jsonData = json_encode(array(
            'serviceName' => Sankhya::$serviceNameQuery,
            'requestBody' => array(
                    'sql' => "$sqlExecute"
            )
        ), JSON_PRETTY_PRINT);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => Sankhya::$urlApiQuery,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                Sankhya::$contentType . 'json', // Se a requisição for JSON
                Sankhya::$Bearer . $tokenSankhya['rows'],
            ),
            CURLOPT_POSTFIELDS => $jsonData,
        ]);

        $result = curl_exec($curl);

        $jsonResult = json_decode($result, true);

        // Verifica se a API executou
        if ($jsonResult['error'] ?? '') {
            return array(
                "rows" => [],
                "errorCode" => 1,
                "errorMessage" => $jsonResult['error']['descricao']
            );
        }

        if (!$jsonResult['status']) {
            return array(
                "rows" => [],
                "errorCode" => 1,
                "errorMessage" => $jsonResult['statusMessage']
            );
        }

        // Fechar a sessão cURL
        curl_close($curl);
        $rows = json_decode($result, true)['responseBody']['rows'];

        return array(
            "rows" => $rows,
            "errorCode" => 0,
            "errorMessage" => "ok"
        );
    }

    public static function serviceExecuteAPI($url, $requestBody, $json = true): array
    {
        $tokenSankhya = Sankhya::bearerToken();

        // Verifica se a API executou
        if ($tokenSankhya['errorCode']) {
            return array(
                "rows" => $tokenSankhya['rows'],
                "errorCode" => $tokenSankhya['errorCode'] ?? 1,
                "errorMessage" => $tokenSankhya['errorMessage']
            );
        }

        if ($json) {
            $body = json_encode(array(
                'requestBody' => $requestBody
            ), JSON_UNESCAPED_SLASHES);
            $contentType = Sankhya::$contentType . 'json';
        } else {
            $body = $requestBody;
            $contentType = Sankhya::$contentType . 'xml';
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                $contentType,
                Sankhya::$Bearer . $tokenSankhya['rows'],
            ),
            CURLOPT_POSTFIELDS => $body,
        ]);

        $result = curl_exec($curl);

        // Fechar a sessão cURL
        curl_close($curl);

        if (!$json) {
            $string = str_replace('"', "'", $result);     // substitui aspas dupla po simples
            $string = str_replace(array("\r", "\n"), '', $string); // tira o cr/lf

            $xml = [];

            try {
                $xml = simplexml_load_string($string, 'SimpleXMLElement', LIBXML_NOCDATA);
            } catch(\Exception $e) {

                if (!$xml['status']) {
                    return array(
                        "rows" => [],
                        "errorCode" => 1,
                        "errorMessage" => "Erro ao tentar ler XML de retorno do Sankhya na execução da API $url. <br> {$e->getMessage()}"
                    );
                }
            }

            if ("{$xml['status']}" == '0') {
                return array(
                    "rows" => [],
                    "errorCode" => 1,
                    "errorMessage" => base64_decode($xml->statusMessage)
                );
            }

            return array(
                "rows" => [],
                "errorCode" => 0,
                "errorMessage" => 'ok'
            );
        }

        $jsonResult = json_decode($result, true);

        // Verifica se a API executou
        if ($jsonResult['error'] ?? '') {
            return array(
                "rows" => [],
                "errorCode" => 1,
                "errorMessage" => $jsonResult['error']['descricao']
            );
        }

        if (!$jsonResult['status']) {
            return array(
                "rows" => [],
                "errorCode" => 2,
                "errorMessage" => $jsonResult['statusMessage']
            );
        }

        $rows = $jsonResult['responseBody'];

        return array(
            "rows" => $rows,
            "errorCode" => 0,
            "errorMessage" => "ok"
        );
    }

}
