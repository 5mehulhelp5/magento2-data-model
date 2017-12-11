<?php
namespace SnowIO\Magento2DataModel\Transform;

use Joshdifabio\Transform\MapElements;
use Joshdifabio\Transform\Pipeline;
use Joshdifabio\Transform\Transform;
use SnowIO\Magento2DataModel\ProductData;
use SnowIO\Magento2DataModel\Command\DeleteProductCommand;

final class CreateDeleteProductCommands
{
    public static function fromIterables(): Transform
    {
        return Pipeline::of(
            CreateDiffs::fromIterables(function (ProductData $productData) {
                return \implode(' ', [$productData->getSku(), $productData->getStoreCode()]);
            }),
            self::fromDiffs()
        );
    }

    public static function fromDiffs(): Transform
    {
        return CreateDeleteCommands::fromDiffs(self::fromProductData());
    }

    public static function fromProductData(): Transform
    {
        return MapElements::via(function (ProductData $previousProductData) {
            return DeleteProductCommand::of($previousProductData->getSku());
        });
    }

    public static function fromSkus(): Transform
    {
        return MapElements::via(function (string $sku) {
            return DeleteProductCommand::of($sku);
        });
    }
}
