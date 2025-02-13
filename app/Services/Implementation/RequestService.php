<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use Psr\Http\Message\ServerRequestInterface;
use JR\ChefsDiary\DataObjects\Data\DataTableQueryParams;

class RequestService
{
    public function __construct(
        private readonly SessionService $session
    ) {
    }

    public function getReferer(ServerRequestInterface $request): string
    {
        $referer = $request->getHeader('referer')[0] ?? '';

        if (!$referer) {
            return $this->session->get('previousUrl');
        }

        $refererHost = parse_url($referer, PHP_URL_HOST);

        if ($refererHost !== $request->getUri()->getHost()) {
            $referer = $this->session->get('previousUrl');
        }

        return $referer;
    }

    public function isXhr(ServerRequestInterface $request): bool
    {
        return $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
    }

    public function getDataTableQueryParameters(ServerRequestInterface $request): DataTableQueryParams
    {
        $params = $request->getQueryParams();

        $orderBy = $params['columns'][$params['order'][0]['column']]['data'] ?? '';
        $orderDir = $params['order'][0]['dir'] ?? '';

        return new DataTableQueryParams(
            (int) $params['start'] ?? 0,
            !!$params['length'] ? ((int) $params['length']) : null,
            $orderBy,
            $orderDir,
            $params['search']['value'] ?? '',
            (int) $params['draw']
        );
    }

    public function getClientIp(ServerRequestInterface $request, array $trustedProxies): ?string
    {
        $serverParams = $request->getServerParams();

        if (
            in_array($serverParams['REMOTE_ADDR'], $trustedProxies, true)
            && isset($serverParams['HTTP_X_FORWARDED_FOR'])
        ) {
            $ips = explode(',', $serverParams['HTTP_X_FORWARDED_FOR']);

            return trim($ips[0]);
        }

        return $serverParams['REMOTE_ADDR'] ?? null;
    }
}