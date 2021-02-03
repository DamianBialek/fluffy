<?php


namespace App\Allegro;


use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Api
{
    protected $httpClient;
    protected $accessTokenObject;
    protected $accessTokenIsRefreshed = false;
    protected $maxUnautorizedRequests = 4;
    protected $unautorizedRequests = 0;

    public function __construct($accessTokenObject = null)
    {
        $this->httpClient = new Client();
        $this->accessTokenObject = $accessTokenObject;
    }

    private function getNewAccessToken()
    {
        try {
            $res = $this->httpClient->request("POST", "https://allegro.pl/auth/oauth/token?grant_type=client_credentials", [
                'headers' => [
                    'Authorization' => "Basic ".$this->getOauthAuthorizationToken()
                ]
            ]);

            $this->accessTokenIsRefreshed = true;

            return json_decode($res->getBody(), true);
        } catch (\Exception $e) {
            $this->throwAllegroException();
        }
    }

    private function decodeAccessToken($token)
    {
        return JWTAuth::parseToken($token)->getPayload()->toArray();
    }

    public function getAccessToken()
    {
        if(!$this->accessTokenObject) {
            $this->accessTokenObject = $this->getNewAccessToken();
        }

        return $this->accessTokenObject['access_token'];
    }

    /**
     * @return mixed|null
     */
    public function getAccessTokenObject()
    {
        return $this->accessTokenObject;
    }

    public function offersListing($phrase, $offset = 0, $limit = 15)
    {
        $res = $this->apiRequest("GET", "https://api.allegro.pl/offers/listing?searchMode=DESCRIPTIONS&limit={$limit}&offset={$offset}&phrase=".urlencode($phrase));

        $data = json_decode($res->getBody(), true);
        $data = array_intersect_key($data, array_flip(['items', 'searchMeta']));

        $items = array_merge([], $data['items']['promoted'], $data['items']['regular']);
        $data['items'] = $items;

        return $data;
    }

    private function getOauthAuthorizationToken()
    {
        $clientId = config("allegro.client_id");
        $clientSecret = config("allegro.client_secret");

        if(empty($clientId) || empty($clientSecret)) {
            $this->throwAllegroException();
        }

        return base64_encode($clientId.":".$clientSecret);
    }

    /**
     * @return bool
     */
    public function isAccessTokenRefreshed(): bool
    {
        return $this->accessTokenIsRefreshed;
    }

    private function apiRequest($method, $url, $headers = [])
    {
        try {
            return $this->httpClient->request($method, $url, [
                'headers' => array_merge($headers, [
                    'Accept' => 'application/vnd.allegro.public.v1+json',
                    'Authorization' => "Bearer ".$this->getAccessToken()
                ])
            ]);
        } catch (ClientException $e) {
            if($e->getCode() == '401') {
                $this->unautorizedRequests++;

                if($this->unautorizedRequests > $this->maxUnautorizedRequests) {
                    $this->throwAllegroException();
                }

                if($json = json_decode((string)$e->getResponse()->getBody(), true)) {
                    if($json['error'] == 'invalid_token') {
                        $this->accessTokenObject = $this->getNewAccessToken();
                    }
                }


                return $this->apiRequest($method, $url, $headers);
            } else {
                $this->throwAllegroException();
            }

            throw $e;
        }
    }

    private function throwAllegroException()
    {
        throw new \Exception("Connection error with Allegro API ! Please check API keys and try again later");
    }

    public function debugInfo()
    {
        return [
            'unautorizedRequests' => $this->unautorizedRequests
        ];
    }
}
