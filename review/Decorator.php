<?php

namespace src\Decorator;

//fixme: use DateTime и Exception лучше заменить на вызовы \ в самом коде
use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

/**
 * fixme: Безусловно, есть разные подходы к реализации паттерна Декоратор, но это НЕ он.
 * Ключевая задача декоратора - расширять функциональность методов переданного объекта,
 * добавлять новые возможности объектам без необходимости создания подклассов.
 * Ключевой особенностью можно считать возможность выстраивания цепочек вызовов декорируемых объектов.
 * В текущем же подходе, мы помимо прочего жестко связываем классы.
 *
 * Проще говоря, предпочитаем композицию наследованию, класс необходимо переименовать
 *
 * fixme: PHPDoc класса
 */
class DecoratorManager extends DataProvider
{
    //fixme: использование публичных свойств - плохо, т.к. их изменение не поддается контролю
    //например, в нашем контексте логгер может быть не задан и эта проблема может не проявится сразу
    //как следствие - все свойства сделать private или protected (в зависимости от задачи), добавить геттеры и сеттеры
    //добавить PHPDoc, описать типы
    public $cache;
    public $logger;

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param CacheItemPoolInterface $cache
     */
    public function __construct($host, $user, $password, CacheItemPoolInterface $cache)
    {
        parent::__construct($host, $user, $password);
        $this->cache = $cache;
    }

    /**
     * fixme:
     * Данным методом маскируется проблема public свойств (см. выше). При этом, исходя из кода класса, всё будет плохо, если логгер не задан
     * (мы не будем получать сообщения об ошибках и все будет падать). Поэтому для данного класса он обязателен и в текущем виде его обязательно нужно
     * устанавливать на этапе создания объекта. Этот метод нужно убрать.
     *
     * Вообще, по хорошему, и логгер и CacheItemPool добавлять через DI
     *
     * */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * fixme: По сути - это есть расширение метода get родительского класса. Метод нужно переименовать в родительский.
     * в этом случае появляется смысл в @inheritdoc
     * fixme: $input вообще очень плохое название для входного параметра т.к. не дает понимание
     * что должно быть передано на вход. В любом случае, интерфейс должен совпадать с родительским (здесь это не так)
     */

    /**
     * {@inheritdoc}
     */
    public function getResponse(array $input)
    {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            /* fixme: если окажется, что мы хотим иметь два разных метода, тогда вызывать имеет смысл $this->get... */
            $result = parent::get($input);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify('+1 day')
                );

            /*
             * fixme: Согласно PSR забыли вызвать $this->cache->save($cacheItem),
             * т.е. в данном случае кэш у нас никогда не будет использоваться
             */

            return $result;
        } catch (Exception $e) {
            /**
             * fixme:
             * Сообщения в лог должны быть информитивными и полезными, чтобы можно было быстро найти проблему и устранить
             * (тем более если у нас речь о critical сценарии)
             * Помимо информативного пояснения по хорошему включать максимум полезной информации включая $e->getMessage()
             *
             * fixme: Возможно, имеет смысл прервать выполнение (красиво, без вывода пользователю деталей ошибки),
             * а не продолжать после записи. Зависит от того, что мы тут ловим.
             * Если кэш можно и забить, то при проблеме получения данных - нет. По хорошему нужны разные Exception.
             */

            $this->logger->critical('Error');
        }

        return [];
    }

    /**
     * fixme: Метод не должен быть публичным, он не должен относится к интерфейсу данного класса
     * fixme: PHPDoc
     * */
    public function getCacheKey(array $input)
    {
        /**
         * fixme: json_encode тяжелая операция,
         * лучше, если мы не можем уйти от массива, воспользоваться, например, implode'ом
         */
        return json_encode($input);
    }
}