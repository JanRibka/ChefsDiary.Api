<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use Psr\Http\Message\ServerRequestInterface;

class RequestService
{
    public function isXhr(ServerRequestInterface $request): bool
    {
        return $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
    }
}