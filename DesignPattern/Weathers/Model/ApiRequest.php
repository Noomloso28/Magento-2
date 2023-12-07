<?php

namespace DesignPattern\Weathers\Model;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseFactory;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Webapi\Rest\Request;
use Psr\Log\LoggerInterface;

class ApiRequest
{
    /** @var ClientFactory */
    private $clientFactory;

    /** @var ResponseFactory */
    private $responseFactory;

    /** @var SerializerInterface */
    private $serializer;

    /** @var DataObjectFactory */
    private $dataObjectFactory;
    private LoggerInterface $logger;

    public function __construct(
        ClientFactory $clientFactory,
        ResponseFactory $responseFactory,
        string $requestUri,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ) {
        $this->clientFactory = $clientFactory;
        $this->responseFactory = $responseFactory;
        $this->requestUri = $requestUri;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    public function execute(
        string $uriEndpoint,
        array $params = [],
        string $requestMethod = Request::HTTP_METHOD_GET
    ): array {
        /** @var Client $client */
        $client = $this->clientFactory->create(['config' => [
            'base_uri' => $this->requestUri
        ]]);

        try {
            $response = $client->request(
                $requestMethod,
                $uriEndpoint,
                $params
            );
        } catch (GuzzleException $exception) {
            /** @var Response $response */
            $response = $this->responseFactory->create([
                'status' => 400,
                'reason' => $exception->getMessage()
            ]);
        }

        //$this->logger->debug($response->getBody());

        return $this->serializer->unserialize($response->getBody());
    }
}
