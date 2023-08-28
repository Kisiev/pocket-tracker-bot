<?php

namespace Modules\Notion\Services;

use App\Events\ChargesExportedEvent;
use App\Models\Category;
use App\Models\User;
use App\Services\Exporter;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Modules\Notion\Client\NotionClient;

class ExportChargeService implements Exporter
{
    private ?User $user = null;

    public function __construct(private readonly NotionClient $notionClient)
    {
    }

    private function init(User $user)
    {
        $this->user = $user;
    }

    public function export(User $user): void
    {
        $this->init($user);

        try {
            $blocks = $this->notionClient->getBlockChildren(
                $user->settings->notionSecret,
                $user->settings->notionBlockId
            );

            $this->removePageContent($blocks);

            $categories = Category::query()->where('user_id', $user->id)->with('charges')->get();

            $params = $this->buildRequestParams($categories);

            $this->notionClient->updateBlock($user->settings->notionSecret, $this->user->settings->notionBlockId, $params);

            event(new ChargesExportedEvent($user, 'Данные выгружены'));
        } catch (Exception $e) {
            Log::error($e->getMessage(), [
                'user_id' => $user->id,
                'request_params' => $params ?? null,
            ]);

            event(new ChargesExportedEvent($user, 'Ошибка выгрузки'));
        }
    }

    private function removePageContent(array $blocks): void
    {
        foreach ($blocks['results'] ?? [] as $block) {
            $this->notionClient->deleteBlock($this->user->settings->notionSecret, $block['id']);
        }
    }

    /**
     * @param Collection $categories
     * @return array
     */
    private function buildRequestParams(Collection $categories): array
    {
        $params['children'] = [];
        $total = 0;

        /** @var Category $category */
        foreach ($categories as $category) {
            $chargeParams = [];

            foreach ($category->charges as $charge) {
                $total += $charge->cost;

                $chargeParams[] = [
                    'type' => 'text',
                    'text' => [
                        'content' => "\n    " . number_format($charge->cost, 0, '.', ' ') . " - " . $charge->title,
                        'link'    => null,
                    ],
                ];
            }

            $params['children'][] = [
                'object' => 'block',
                'type' => 'paragraph',
                'paragraph' => [
                    'rich_text' => array_merge([
                        [
                            'type' => 'text',
                            'text' => [
                                'content' => $category->title,
                                'link' => null,
                            ],
                            'annotations' => [
                                'bold' => true,
                            ]
                        ]
                    ], $chargeParams),
                ],
            ];
        }
        $params['children'][] = [
            'object' => 'block',
            'type' => 'paragraph',
            'paragraph' => [
                'rich_text' => [
                    [
                        'type' => 'text',
                        'text' => [
                            'content' => 'Итого: ' . number_format($total, 0, '.', ' '),
                            'link' => null,
                        ],
                        'annotations' => [
                            'bold' => true,
                        ]
                    ]
                ],
            ],
        ];

        return $params;
    }
}
