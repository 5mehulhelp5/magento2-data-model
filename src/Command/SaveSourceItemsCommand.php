<?php

namespace SnowIO\Magento2DataModel\Command;

use SnowIO\Magento2DataModel\Inventory\SourceItemDataSet;

class SaveSourceItemsCommand extends Command
{
    public static function of(SourceItemDataSet $sourceItemDataSet): self
    {
        $result = new self;
        $result->sourceItems = $sourceItemDataSet;
        return $result;
    }

    public function getSourceItems(): SourceItemDataSet
    {
        return $this->sourceItems;
    }

    public function withStore(string $store): self
    {
        $result = clone $this;
        $result->store = $store;
        return $result;
    }

    public function toJson(): array
    {
        return parent::toJson() + [
            "sourceItems" => $this->sourceItems->toJson(),
            "@store" => $this->store,
        ];
    }

    /** @var SourceItemDataSet */
    private $sourceItems;
    private $store;

    private function __construct()
    {
    }
}
