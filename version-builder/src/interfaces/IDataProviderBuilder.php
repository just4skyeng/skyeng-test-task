<?php

namespace src\interfaces;

/**
 * Interface IDataProviderBuilder
 * @package src\interfaces
 */
interface IDataProviderBuilder
{
    /**
     * @param string $host
     * @return mixed
     */
    public function setHost(string $host);

    /**
     * @param string $user
     * @return mixed
     */
    public function setUser(string $user);

    /**
     * @param string $password
     * @return mixed
     */
    public function setPassword(string $password);

    /**
     * @param CacheItemPoolInterface $cacheItemPool
     * @return mixed
     */
    public function setCacheItemPool(CacheItemPoolInterface $cacheItemPool);

    /**
     * @param LoggerInterface $logger
     * @return mixed
     */
    public function setLogger(LoggerInterface $logger);

    /**
     * Возвращаем результат нашего "строительства" (сам провайдер)
     * @return IDataProvider
     */
    public function getResult(): IDataProvider;
}