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

class IndexController extends AbstractController
{
    public function index()
    {
            $queryType = new ObjectType([
        'name' => 'Query',
        'fields' => [
            'echo' => [
                'type' => Type::string(),
                'args' => [
                    'message' => ['type' => Type::string()],
                ],
                'resolve' => static fn ($rootValue, array $args): string => $rootValue['prefix'] . $args['message'],
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
                'resolve' => static fn ($calc, array $args): int => $args['x'] + $args['y'],
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
    $query = $this->request->input('query');
    $variableValues = $this->request->input('variables', null);
    $rootValue = ['prefix' => 'You said: '];
    $result = GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
    $output = $result->toArray();
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
            'output' => $output
        ];
    }
}
