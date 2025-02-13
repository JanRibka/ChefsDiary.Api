<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Shared\ResponseFormatter;

use Psr\Http\Message\ResponseInterface;

class ResponseFormatter
{
    public function asJson(
        ResponseInterface $response,
        mixed $data,
        int $flag = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS | JSON_THROW_ON_ERROR
    ): ResponseInterface {
        $response = $response->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(json_encode($data, $flag));

        return $response;
    }

    public function asDataTable(
        ResponseInterface $response,
        array $data,
        int $draw,
        int $total
    ): ResponseInterface {
        return $this->asJson(
            $response,
            [
                'data' => $data,
                'draw' => $draw,
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
            ]
        );
    }
}