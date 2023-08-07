<?php

namespace App\Api;

use App\Entity\Formation;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    public function __construct(
        private HttpClientInterface $client,
        private ParameterBagInterface $params,
    ) {
    }
    public function getEntities($method, $entity, $itemsPerPage= 5){

        $array = [];
       
        $api_formation = $this->params->get('API_HERMES_TEMPLATES').$entity;
        if($itemsPerPage){
            $api_formation .= "?itemsPerPage=$itemsPerPage ";
        }

        try{
            $response = $this->client->request(
                $method,
                $api_formation,
            );
            $jsonsl_content = $response->getContent();
            $array = json_decode($response->getContent(), true);
        }catch(Exception $e){

        }

        return $array;

    }

    public function getTemplates($entity, $itemsPerPage= 5){
        $array = [];

        $entities = $this->getEntities('GET', $entity, $itemsPerPage);
        if(isset($entities['hydra:member']))
        {
            $array = $entities['hydra:member'];
        }

        return $array;

    }

    public function handleEntitie($method, $entity, $id){

        $api_formation = $this->params->get('API_HERMES_TEMPLATES').$entity. "/$id";

        try{
            $response = $this->client->request(
                $method,
                $api_formation,
            );

            $jsonld_content = $response->getContent();

            $array = json_decode($response->getContent(), true);
        }catch(Exception $e){

        }

        return $array;

    }


}
