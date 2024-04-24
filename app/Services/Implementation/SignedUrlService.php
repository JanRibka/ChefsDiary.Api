<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use DateTime;
use JR\ChefsDiary\Config;
use Slim\Interfaces\RouteParserInterface;

class SignedUrlService
{
    public function __construct(
        private readonly Config $config,
        private readonly RouteParserInterface $routeParser
    ) {
    }

    public function fromRoute(string $routeName, array $routeParams, DateTime $expirationDate): string
    {
        $expiration = $expirationDate->getTimestamp();
        $queryParams = ['expiration' => $expiration];
        $baseUrl = trim($this->config->get('app_url'), '/');
        $url = $baseUrl . $this->routeParser->urlFor($routeName, $routeParams, $queryParams);

        $signature = hash_hmac('sha256', $url, $this->config->get('app_key'));

        return $baseUrl . $this->routeParser->urlFor(
            $routeName,
            $routeParams,
            $queryParams + ['signature' => $signature]
        );
    }
}