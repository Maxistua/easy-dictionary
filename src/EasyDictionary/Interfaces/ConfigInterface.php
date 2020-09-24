<?php

namespace EasyDictionary\Interfaces;

use Psr\SimpleCache\CacheInterface;

/**
 * Interface ConfigInterface
 *
 * @package EasyDictionary
 */
interface ConfigInterface
{
    const DEFAULT_CACHE_TTL = 60;

    /**
     * @return string
     */
    public function getDefaultDataProviderClass(): string;

    /**
     * @return string
     */
    public function getDefaultDictionaryClass(): string;

    /**
     * @return array
     */
    public function getDictionaryConfig(): array;

    /**
     * @return null|callable
     */
    public function getDefaultView(): ?callable;

    /**
     * @param string $name
     * @return null|CacheInterface
     */
    public function getCache(string $name): ?CacheInterface;
}
