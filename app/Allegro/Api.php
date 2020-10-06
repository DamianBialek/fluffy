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
        $this->refreshIfExpiredAccessToken();
    }

    private function getNewAccessToken()
    {
        $res = $this->httpClient->request("POST", "https://allegro.pl/auth/oauth/token?grant_type=client_credentials", [
            'headers' => [
                'Authorization' => "Basic ".$this->getOauthAuthorizationToken()
            ]
        ]);

        $this->accessTokenIsRefreshed = true;

        return json_decode($res->getBody(), true);
    }

    private function refreshIfExpiredAccessToken()
    {
        if(empty($this->accessTokenObject) || empty($this->accessTokenObject['access_token'])) {
            $this->accessTokenObject = $this->getNewAccessToken();
        }

        $decodedToken = $this->decodeAccessToken($this->accessTokenObject['access_token']);

        if($this->checkIfTokenIsExpired($decodedToken)) {
            $this->accessTokenObject = $this->getNewAccessToken();
        }
    }

    private function checkIfTokenIsExpired($decodedToken)
    {
        if(empty($decodedToken['exp'])) {
            return true;
        }

        return Carbon::parse($decodedToken['exp']) < Carbon::now();
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
        $res = $this->apiRequest("GET", "https://api.allegro.pl/offers/listing?searchMode=DESCRIPTIONS&limit={$limit}&offset={$offset}&phrase=".urlencode($phrase), [
            'Accept' => 'application/vnd.allegro.public.v1+json',
            'Authorization' => "Bearer ".$this->getAccessToken()
        ]);

        $data = json_decode($res->getBody(), true);
        $data = array_intersect_key($data, array_flip(['items', 'searchMeta']));

        $items = array_merge([], $data['items']['promoted'], $data['items']['regular']);
        $data['items'] = $items;

        return $data;
    }

    private function getOauthAuthorizationToken()
    {
        return base64_encode(config("allegro.client_id").":".config("allegro.client_secret"));
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
                'headers' => $headers
            ]);
        } catch (ClientException $e) {
            if($e->getCode() == '401') {
                $this->unautorizedRequests++;

                if($this->unautorizedRequests > $this->maxUnautorizedRequests) {
                    throw new \Exception("Max unautorized requests limit reached !");
                }

                $this->refreshIfExpiredAccessToken();
                return $this->apiRequest($method, $url, $headers);
            }

            throw $e;
        }
    }

    public function debugInfo()
    {
        return [
            'unautorizedRequests' => $this->unautorizedRequests
        ];
    }
}
