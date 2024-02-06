<?php declare(strict_types=1);

namespace App\Examples\Blog\Type;

use App\Examples\Blog\Data\Image;
use App\Examples\Blog\Data\Story;
use App\Examples\Blog\Data\User;
use App\Examples\Blog\Types;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Utils\Utils;

class NodeType extends InterfaceType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Node',
            'fields' => [
                'id' => Types::id(),
            ],
            'resolveType' => [$this, 'resolveNodeType'],
        ]);
    }

    /**
     * @param mixed $object
     *
     * @throws \Exception
     *
     * @return callable(): ObjectType
     */
    public function resolveNodeType($object)
    {
        if ($object instanceof User) {
            return Types::user();
        }

        if ($object instanceof Image) {
            return Types::image();
        }

        if ($object instanceof Story) {
            return Types::story();
        }

        $notNode = Utils::printSafe($object);
        throw new \Exception("Unknown type: {$notNode}");
    }
}