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

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Di\Annotation\Inject;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class UploadController extends AbstractController
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

    public function index()
    {
        $files = $this->request->file("upload");
        var_dump($files);
    }
}
