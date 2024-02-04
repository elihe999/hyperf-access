<?php declare(strict_types=1);

namespace App\Examples\Blog;

use App\Examples\Blog\Data\User;

/**
 * Instance available in all GraphQL resolvers as 3rd argument.
 */
class AppContext
{
    public string $rootUrl;

    public User $viewer;

    /** @var array<string, mixed> */
    public array $request;
}
