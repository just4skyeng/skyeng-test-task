<?php


namespace app\Integration;

use core\SimpleDecorator;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

/**
 * Class SomeExternalServiceDataProviderWithCache
 * @package app\Integration
 */
class SomeExternalServiceDataProviderWithCache extends SimpleDecorator
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cache;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SomeExternalServiceDataProviderWithCache constructor.
     * @param array ...$arguments
     * @internal LoggerInterface $logger
     * @internal CacheItemPoolInterface $cache
     * @throws \Exception
     */
    public function __construct(...$arguments)
    {
        parent::__construct(...$arguments);

        $this->logger = $arguments[1];
        if (! ($this->logger instanceof LoggerInterface)) {
            throw new \Exception('Logger должен имплементировать LoggerInterface');
        }

        $this->cache = $arguments[2];
        if (! ($this->cache instanceof CacheItemPoolInterface)) {
            throw new \Exception('Cache должен имплементировать CacheItemPoolInterface');
        }
    }

    /**
     * Переопределяем метод (добавляем кэширование
     *
     * @param array $response
     * @return mixed
     */
    public function getData(array $response)
    {
        try {
            $cacheKey = $this->getCacheKey($response);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $data = $this->object->getData();

            $cacheItem
                ->set($data)
                ->expiresAt(
                    (new \DateTime())->modify('+1 day')
                );

            $this->cache->save($cacheItem);

            return $data;
        } catch (\Exception $e) {
            //да, тут бы два catch
            $this->logger->critical('Ошибка века, что-то случилось с кэшем, или с данными от нашего стороннего сервиса');
            exit;
        }
    }

    /**
     * Генерируем ключ для кэша
     *
     * @param array $input
     * @return string
     */
    private function getCacheKey(array $input)
    {
        return implode('.', $input);
    }
}