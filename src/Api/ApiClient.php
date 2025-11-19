<?php

namespace App\Api;

use App\Service\EncryptionService;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    private $session;
    public function __construct(
        private HttpClientInterface $client,
        private RequestStack $request,
        private ParameterBagInterface $params,
        private EncryptionService $encryptionService
    ) {
        $this->session = $request->getSession();
    }

    public function login(string $email, string $password): ?string
    {
        // @todo : supprimer API_HERMES_NOT_JWT_VERSION une fois api-hermes-cms.prg avec Lexik-jwt-token
        $isNotJwtVersion = $this->params->get('API_HERMES_NOT_JWT_VERSION');
        if($isNotJwtVersion){
            $jwt = $this->session->set('jwt', 'azeaezaeaz');
            return $jwt;
        }

        $isHermesCms = $this->params->get('API_HERMES_IS_CMS');
        $key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);

        $encryptedEmail = $this->encryptionService->encrypt($email, $key);
        $encryptedPassword = $this->encryptionService->encrypt($password, $key);
        try{
            $json = [
                    'email' => $encryptedEmail['encryptedData'],
                    'nonceEmail' => $encryptedEmail['nonce'],
                    'password' => $encryptedPassword['encryptedData'],
                    'noncePassword' => $encryptedPassword['nonce'],
                    'key' => base64_encode($key),
                    'isHermesCms' => $isHermesCms
            ];
            // dd(json_encode($json));
            $response = $this->client->request('POST', $this->params->get('API_HERMES_BASE_URL').'/api/login', [
                'json' => $json,
            ]);

            if ($response->getStatusCode() === 200)  {
                $data = $response->toArray();
                $jwt = $data['token'];
                $this->session->set('jwt', $jwt);
                return $jwt;
            }
        }catch(Exception $e){
             //dd($e->getMessage());
        }

        return null;
    }

    public function getEntities($method, $entity, $itemsPerPage= 5){

        //$this->session->set('jwt', null); // reset session uniquement pour tester

        $array = [];
        try{

            $email = $this->params->get('API_HERMES_EMAIL'); //'api@hermes-cms.org';
            $password = $this->params->get('API_HERMES_PASSWORD'); //'mdpadminapi';
            $this->login($email, $password);
            $token = $this->session->get('jwt');
            if (!$token) {
                throw new \RuntimeException('No JWT token found.');
            }
            $array = [];

            $api_formation = $this->params->get('API_HERMES_TEMPLATES').$entity;
            if($itemsPerPage){
                $api_formation .= "?itemsPerPage=$itemsPerPage";
            }

            $response = $this->client->request(
                $method,
                $api_formation,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/ld+json',
                    ],
                ]
            );
            $array = json_decode($response->getContent(), true);
        }catch(Exception $e){
                //dd($e->getMessage());
        }

        return $array;

    }

    public function getTemplates($entity, $itemsPerPage= 5){
        $array = [];

        $entities = $this->getEntities('GET', $entity, $itemsPerPage);
        // api-platform v3
        if(isset($entities['hydra:member']))
        {
            $array = $entities['hydra:member'];
        }
        // api-platform v4
        if(isset($entities['member']))
        {
            $array = $entities['member'];
        }

        return $array;

    }

    public function handleEntitie($method, $entity, $id){

        $token = $this->session->get('jwt');
        if (!$token) {
            throw new \RuntimeException('No JWT token found.');
        }

        $api_formation = $this->params->get('API_HERMES_TEMPLATES').$entity. "/$id";

        try{
            $response = $this->client->request(
                $method,
                $api_formation,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/ld+json',
                    ],
                ]
            );

            $array = json_decode($response->getContent(), true);
        }catch(Exception $e){
            dd($e->getMessage());
        }

        return $array;

    }

}
