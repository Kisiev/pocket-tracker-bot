<?php

namespace Modules\Notion\Client;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class NotionClient
{
    public function updateBlock(string $secret, string $blockId, array $params = []): void
    {
        $response = $this->send($secret, 'PATCH', "/v1/blocks/{$blockId}/children", $params);

        if ($response->status() !== ResponseAlias::HTTP_OK) {
            throw new Exception('cannot delete block:' . $response->body());
        }
    }

    public function deleteBlock(string $secret, string $blockId): void
    {
        $response = $this->send($secret, 'DELETE', "/v1/blocks/{$blockId}");

        if ($response->status() !== ResponseAlias::HTTP_OK) {
            throw new Exception('cannot delete block: ' . $response->body());
        }
    }

    public function getBlockChildren(string $secret, string $blockId): array
    {
        $response = $this->send($secret, 'GET', "/v1/blocks/{$blockId}/children");

        if ($response->status() !== ResponseAlias::HTTP_OK) {
            throw new Exception('get block exception: ' . $response->body());
        }

        return $response->json();
    }

    private function send(string $secret, string $method, string $url, array $params = []): Response
    {
        return Http::withToken($secret)
            ->withHeader('Notion-Version', '2022-06-28')
            ->{$method}(env('NOTION_URL') . $url, $params);
    }
}
