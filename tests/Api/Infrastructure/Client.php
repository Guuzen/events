<?php

declare(strict_types=1);

namespace Tests\Api\Infrastructure;

use Google\Auth\Cache\MemoryCacheItemPool;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\Schema\BreadCrumb;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\stream_for;

final class Client
{
    private $client;

    public function __construct(string $uri, string $loggerName, array $headers)
    {
        $isVerbose   = \in_array('--verbose', $_SERVER['argv'], true);
        $middlewares = HandlerStack::create();
        $yamlFile  = '/var/www/html/openapi/stoplight.yaml';
        $validator = (new \League\OpenAPIValidation\PSR7\ValidatorBuilder)
            ->fromYamlFile($yamlFile)
            ->getResponseValidator();

        $middlewares->push(
            static function (callable $handler) use ($validator) {
                return static function (RequestInterface $request, array $options) use ($handler, $validator) {
                    return $handler($request, $options)->then(
                        static function (ResponseInterface $response) use ($request, $validator) {
                            $uri       = $request->getUri()->getPath();
                            $method    = mb_strtolower($request->getMethod());
                            $operation = new \League\OpenAPIValidation\PSR7\OperationAddress($uri, $method);

                            try {
                                $validator->validate($operation, $response);
                            } catch (ValidationFailed $validationFailed) {
                                $previosException = $validationFailed->getPrevious();
                                if ($previosException instanceof SchemaMismatch) {
                                    /** @var BreadCrumb $breadCrumb */
                                    $breadCrumb      = $previosException->dataBreadCrumb();
                                    $breadCrumbChain = $breadCrumb->buildChain();
                                    $message         = $previosException->getMessage() . ' ' . \implode('.', $breadCrumbChain);
                                } else {
                                    $message = $validationFailed->getMessage();
                                }
                                throw new \RuntimeException($message);
                            }

                            return $response;
                        }
                    );
                };
            }
        );

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
            'headers'  => $headers,
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
