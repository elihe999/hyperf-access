<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use GraphQL\Server\StandardServer;
use GraphQL\Executor\ExecutionResult;
use App\Examples\Blog\AppContext;
use App\Examples\Blog\Data\DataSource;
use App\Examples\Blog\Type\QueryType;
use App\Examples\Blog\Types;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Di\Annotation\Inject;

class IndexController extends AbstractController
{

    /**
     * @Inject()
     * @var Psr7ResponseInterface 
     */
    private $psrResponse;

    
    /**
     * @Inject()
     * @var StreamInterface 
     */
    private $psrBodyStream;

    
    /**
     * @Inject()
     * @var ServerRequestInterface
     */
    private $psrRequest;

    public function index(ResponseInterface $response)
    {
        // // Initialize our fake data source
        // DataSource::init();

        // // $queryType = new ObjectType([
        // //     'name' => 'Query',
        // //     'fields' => [
        // //         'echo' => [
        // //             'type' => Type::string(),
        // //             'args' => [
        // //                 'message' => ['type' => Type::string()],
        // //             ],
        // //             'resolve' => static fn ($rootValue, array $args): string => $rootValue['prefix'] . $args['message'],
        // //         ],
        // //     ],
        // // ]);

        // $mutationType = new ObjectType([
        //     'name' => 'Mutation',
        //     'fields' => [
        //         'sum' => [
        //             'type' => Type::int(),
        //             'args' => [
        //                 'x' => ['type' => Type::int()],
        //                 'y' => ['type' => Type::int()],
        //             ],
        //             'resolve' => static fn ($calc, array $args): int => $args['x'] + $args['y'],
        //         ],
        //     ],
        // ]);

        // // See docs on schema options:
        // // https://webonyx.github.io/graphql-php/schema-definition/#configuration-options
        // // $schema = new Schema(
        // //     (new SchemaConfig())
        // //     ->setQuery($queryType)
        // //     ->setMutation($mutationType)
        // // );
        // $query = $this->request->input('query');
        // $variableValues = $this->request->input('variables', null);
        // $rootValue = ['prefix' => 'You said: '];

        // $schema = new Schema(
        //     (new SchemaConfig())
        //     ->setQuery(new QueryType())
        //     ->setTypeLoader([Types::class, 'load'])
        // );

        // $result = GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
        // $output = $result->toArray();
        // return [
        //     'method' => $method,
        //     'message' => "Hello {$user}.",
        //     'output' => json_encode($result)
        // ];
        // stand server
        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'echo' => [
                    'type' => Type::string(),
                    'args' => [
                        'message' => ['type' => Type::string()],
                    ],
                    'resolve' => static fn (array $rootValue, array $args): string => $rootValue['prefix'] . $args['message'],
                ],
            ],
        ]);
        
        $mutationType = new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'sum' => [
                    'type' => Type::int(),
                    'args' => [
                        'x' => ['type' => Type::int()],
                        'y' => ['type' => Type::int()],
                    ],
                    'resolve' => static fn (array $rootValue, array $args): int => $args['x'] + $args['y'],
                ],
            ],
        ]);
        
        // See docs on schema options:
        // https://webonyx.github.io/graphql-php/schema-definition/#configuration-options
        $schema = new Schema(
            (new SchemaConfig())
            ->setQuery($queryType)
            ->setMutation($mutationType)
        );
        
        $rootValue = ['prefix' => 'You said: '];
        
        // See docs on server options:
        // https://webonyx.github.io/graphql-php/executing-queries/#server-configuration-options
        $server = new StandardServer([
            'schema' => $schema,
            'rootValue' => $rootValue,
        ]);
        /** @var ServerRequestInterface $psrRequest */
        /** @var Psr7ResponseInterface $psrResponse */
        /** @var StreamInterface $psrBodyStream */
        // $psrResponse = new \Hyperf\HttpMessage\Base\Response();
        // $aresponse = $server->processPsrRequest($this->request, $psrResponse, $this->request->getBody());
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        $result = $server->executePsrRequest($this->request);
        // var_dump($aresponse);
        // return $aresponse->withStatus(200, '');
        return [
            'method' => $method,
            'message' => "Hello {$user}.",
            'output' => $result->toArray()
        ];
    }
}
