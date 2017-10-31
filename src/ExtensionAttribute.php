<?php
declare(strict_types=1);
namespace SnowIO\Magento2DataModel;

abstract class ExtensionAttribute
{
    public final function getCode(): string
    {
        return $this->code;
    }

    public final function toJson(): array
    {
        return [
            $this->code => $this->getValueJson(),
        ];
    }

    public function equals($extensionAttribute): bool
    {
        return $extensionAttribute instanceof ExtensionAttribute &&
            ($extensionAttribute->code === $this->code);
    }

    private $code;

    protected function __construct(string $code)
    {
        $this->code = $code;
    }

    protected abstract function getValueJson();
}
