<?php

namespace src\DataProviders;

use src\interfaces\CacheItemPoolInterface;
use src\interfaces\IDataProvider;
use src\interfaces\IDataProviderBuilder;
use src\interfaces\LoggerInterface;

/**
 * Непосредственно строитель провайдеров (он будет возвращать результат, НЕ директор)
 * Class DataProviderBuilder
 * @package src\DataProviders
 */
final class DataProviderBuilder implements IDataProviderBuilder
{
    /**
     * @var
     */
    private $host;
    /**
     * @var
     */
    private $user;
    /**
     * @var
     */
    private $password;
    /**
     * @var
     */
    private $logger;
    /**
     * @var
     */
    private $cacheItemPool;

    /**
     * @param string $host
     * @return mixed|void
     */
    public function setHost(string $host)
    {
        $this->host = $host;
    }

    /**
     * @param string $user
     * @return mixed|void
     */
    public function setUser(string $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $password
     * @return mixed|void
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @param CacheItemPoolInterface $cacheItemPool
     * @return mixed|void
     */
    public function setCacheItemPool(CacheItemPoolInterface $cacheItemPool)
    {
        $this->cacheItemPool = $cacheItemPool;
    }

    /**
     * @param LoggerInterface $logger
     * @return mixed|void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return IDataProvider
     */
    public function getResult(): IDataProvider
    {
        return new DataProvider(
            $this->host,
            $this->user,
            $this->password,
            $this->cacheItemPool,
            $this->logger
        );
    }
}