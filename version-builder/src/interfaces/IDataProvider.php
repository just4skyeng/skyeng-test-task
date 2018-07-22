<?php

namespace src\interfaces;

use src\entity\RequestEntity;

/**
 * Interface IDataProvider
 * @package src\interfaces
 */
interface IDataProvider
{
    /**
     * Единственная задача провайдера - возвращать данные
     * @param RequestEntity $requestEntity
     * @return mixed
     */
    public function getData(RequestEntity $requestEntity);
}