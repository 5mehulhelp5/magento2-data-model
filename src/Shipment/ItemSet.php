<?php
declare(strict_types=1);

namespace SnowIO\Magento2DataModel\Shipment;

use SnowIO\Magento2DataModel\SetTrait;
use SnowIO\Magento2DataModel\ValueObject;

final class ItemSet implements \IteratorAggregate, ValueObject
{
    use SetTrait;

    public function with(Item $item): self
    {
        $result = clone $this;
        $result->items[] = $item;
        return $result;
    }

    public static function fromJson(array $json): self
    {
        $result = self::create();
        foreach ($json as $item) {
            $result = $result->with(Item::fromJson($item));
        }
        return $result;
    }

    public function toJson(): array
    {
        return array_map(function (Item $item) {
            return $item->toJson();
        }, array_values($this->items));
    }

    private static function itemsAreEqual(Item $item, Item $otherItem) : bool
    {
        return $item->equals($otherItem);
    }
}
