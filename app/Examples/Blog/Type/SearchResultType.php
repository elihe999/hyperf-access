<?php declare(strict_types=1);

namespace App\Examples\Blog\Type;

use App\Examples\Blog\Data\Story;
use App\Examples\Blog\Data\User;
use App\Examples\Blog\Types;
use GraphQL\Type\Definition\UnionType;

class SearchResultType extends UnionType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'SearchResult',
            'types' => static fn (): array => [
                Types::story(),
                Types::user(),
            ],
            'resolveType' => static function (object $value): callable {
                if ($value instanceof Story) {
                    return Types::story();
                }

                if ($value instanceof User) {
                    return Types::user();
                }

                $unknownType = \get_class($value);
                throw new \Exception("Unknown type: {$unknownType}");
            },
        ]);
    }
}
