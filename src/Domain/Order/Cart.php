<?php

namespace Kubinyete\KnightShieldSdk\Domain\Order;

use JsonSerializable;

class Cart implements JsonSerializable
{
    protected array $items;

    public function __construct(array $items = [])
    {
        $this->items = [];
        $this->addItems($items);
    }

    public function addItems(array $items): void
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'items' => array_map(function ($x) {
                return $x->jsonSerialize();
            }, $this->items),
        ];
    }
}
