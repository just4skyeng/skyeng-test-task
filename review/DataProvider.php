<?php

namespace src\Integration;

//fixme: добавить PHPDoc классу
class DataProvider
{
    //fixme: от этого класса наследуемся, эти свойства должны быть protected (по крайней мере конкретно в нашем контексте)
    private $host;
    private $user;
    private $password;

    /**
     * fixme: добавить типы параметрам (и в doc, и в интерфейс)
     * @param $host
     * @param $user
     * @param $password
     */
    public function __construct($host, $user, $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @param array $request
     *
     * @return array
     */
    public function get(array $request)
    {
        /**
         * fixme: 1. Название метода слишком абстрактно (getPhotos / getUsers / getЧтоИменноМыПолучаем - предпочтительно и дает понимание с чем мы работаем)
         * fixme: 2. Если мы собираемся передавать какие-то параметры на вход, это лучше делать не в виде единого массива, либо должна быть проверка на соответствие этим входным параметрам
         *    (наличию необходимых параметров и т.д.). Вообще, если мы хотим как-то представлять наш запрос, было бы удобней создать для этого отдельный класс, что позволило бы решить описанные моменты.
         *    В текущем виде проблема в том, что мы можем передать что угодно и не понятно, как планируется использовать данный метод исходя из его интерфейса. Что верно, а что нет.
         */

        // returns a response from external service
    }
}