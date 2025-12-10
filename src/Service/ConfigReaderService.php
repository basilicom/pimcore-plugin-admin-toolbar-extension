<?php

namespace Basilicom\ToolbarExtension\Service;

use Symfony\Component\Serializer\SerializerInterface;

class ConfigReaderService
{
    private array $config;

    public function __construct(
        array                               $config,
        private readonly SerializerInterface $serializer
    )
    {
        $this->config = $config;
    }

    public function getConfig(): array
    {
        return $this->getPreparedConfig();
    }

    public function getConfigAsJson(): string
    {
        return $this->serializer->serialize($this->getPreparedConfig(), 'json');
    }

    private function getPreparedConfig(): array
    {
        $config= [];
        foreach ($this->config as $navEntryPoint => $menuItem) {
            $config[$navEntryPoint] = $this->prepareMenuItem($menuItem);
        }
        return $config;
    }

    private function prepareMenuItem(array $menuItem): array
    {
        if(array_key_exists('menu', $menuItem) && count($menuItem['menu']) > 0) {
            foreach ($menuItem['menu'] as $key => $subMenuItem) {
                $menuItem['menu'][$key] = $this->prepareMenuItem($subMenuItem);
            }
        }
        return $menuItem;
    }
}
