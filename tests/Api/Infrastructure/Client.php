<?php

declare(strict_types=1);

namespace Tests\Api\Infrastructure;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\stream_for;

final class Client
{
    private $client;

    public function __construct(string $uri, string $loggerName, array $defaults)
    {
        $isVerbose   = \in_array('--verbose', $_SERVER['argv'], true);
        $middlewares = HandlerStack::create();
        if ($isVerbose === true) {
            $middlewares->push(Middleware::mapRequest(static function (RequestInterface $request): RequestInterface {
                $requestData   = \json_decode((string)$request->getBody(), true);
                $formattedBody = \json_encode($requestData, JSON_PRETTY_PRINT);

                return $request->withBody(stream_for($formattedBody));
            }));

            $loggerHandler = new StreamHandler('php://stdout');
            $loggerHandler->setFormatter(new LineFormatter(null, null, true, true));
            $logFormat = ">>>>>>>>\n{method} {uri}\n{req_body}\n<<<<<<<<\n{res_body}\n--------\n{error}";
            $middlewares->push(
                Middleware::log(
                    new Logger($loggerName, [$loggerHandler]),
//                    new MessageFormatter(MessageFormatter::DEBUG . "\n----------------------------------------------\n")
                    new MessageFormatter($logFormat . "\n----------------------------------------------\n")
                )
            );

            $middlewares->push(Middleware::mapResponse(static function (ResponseInterface $response): ResponseInterface {
                $responseData  = \json_decode((string)$response->getBody(), true);
                $formattedBody = \json_encode($responseData, JSON_PRETTY_PRINT);

                return $response->withBody(stream_for($formattedBody));
            }));
        }

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $uri,
            'defaults' => $defaults,
            'handler'  => $middlewares,
        ]);
    }

    public function post(string $uri, array $body): array
    {
        $response = $this->client->post($uri, [
            'json' => $body,
        ]);

        return $this->prepareResponse($response);
    }

    public function get(string $uri, array $queryParams): array
    {
        $response = $this->client->get($uri, [
            'query' => $queryParams,
        ]);

        return $this->prepareResponse($response);
    }

    private function prepareResponse(ResponseInterface $response): array
    {
        return \json_decode((string)$response->getBody(), true);
    }
}
