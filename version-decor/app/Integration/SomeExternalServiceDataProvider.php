<?php

namespace app\Integration;

/**
 * Class SomeExternalServiceDataProvider
 * @package app\Integration
 */
class SomeExternalServiceDataProvider
{
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $password;

    /**
     * SomeConcreteExternalDataProvider constructor.
     *
     * @param string $host
     * @param string $user
     * @param string $password
     */
    public function __construct(string $host, string $user, string $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Получить суперважные данные от некоторого конкретного сервиса
     * @param array $request
     *
     * @return array
     */
    public function getData(array $request)
    {
        // TODO: returns a response from external service
    }
}