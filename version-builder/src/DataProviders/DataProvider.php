<?php

namespace src\DataProviders;

use src\interfaces\IDataProvider;

/**
 * Class DataProvider
 * @package src\DataProviders
 */
class DataProvider implements IDataProvider
{
    /**
     * @var string
     */
    protected $host;
    /**
     * @var string
     */
    protected $user;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var CacheItemPoolInterface
     */
    protected $cacheItemPool;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * DataProvider constructor.
     * @param $host
     * @param $user
     * @param $password
     * @param $cacheItemPool
     * @param $logger
     */
    public function __construct(
        string $host,
        string $user,
        string $password,
        CacheItemPoolInterface $cacheItemPool,
        LoggerInterface $logger
    ) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->logger = $logger;
        $this->cacheItemPool = $cacheItemPool;
    }

    /**
     * @param RequestEntity $requestEntity
     * @return array|mixed|void
     */
    public function getData(RequestEntity $requestEntity)
    {
        try {
            $cacheKey = $this->getCacheKey($requestEntity);
            $cacheItem = $this->cacheItemPool->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = $this->getDataFromExternalService($requestEntity);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify('+1 day')
                );

            $this->cacheItemPool->save($cacheItem);

            return $result;
        } catch (ExternalServiceException $e) {
            $this->logger->critical('[Error] - External service is not available - ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical('[Error] - Unexpected error - ' . $e->getMessage());
        }

        return [];
    }

    /**
     * @param RequestEntity $requestEntity
     */
    private function getDataFromExternalService(RequestEntity $requestEntity)
    {
        // returns a response from external service
    }

    /**
     * @param RequestEntity $requestEntity
     * @return string
     */
    private function getCacheKey(RequestEntity $requestEntity)
    {
        //serialize тоже не самая быстрая операция, но тут вопрос в деталях, когда речь о формировании ключа
        return serialize($requestEntity);
    }
}