<?php

/**
 * Директор определяет доступные виды провайдеров и их свойства
 * Class DataProviderBuildDirector
 * @package src\DataProviders
 */
class DataProviderBuildDirector
{
    /**
     * Без использования логирования и кэша
     * @param IDataProviderBuilder $builder
     */
    public function createSimpleDataProvider(IDataProviderBuilder $builder)
    {
        $builder->setHost('hostname_from_config_or_smth_else');
        $builder->setUser('username_from_config_or_smth_else');
        $builder->setPassword('password_from_config_or_smth_else');
        $builder->setLogger(new DummyLogger()); //предполагаем существование провайдера-заглушки для логгера
        $builder->setCacheItemPool(new DummyCacheItemPool()); //предполагаем существование провайдера-заглушки для кэша

        //это позволяет нам обойтись одним классом DataProvider
    }

    /**
     * С логированием и кэшем
     * @param IDataProviderBuilder $builder
     */
    public function createCachedAndLoggedDataProvider(IDataProviderBuilder $builder)
    {
        $builder->setHost('hostname_from_config_or_smth_else');
        $builder->setUser('username_from_config_or_smth_else');
        $builder->setPassword('password_from_config_or_smth_else');
        $builder->setLogger(new FileLogger());
        $builder->setCacheItemPool(new FileCacheItemPool());
    }

    //по аналогии можно предусмотреть поведение с кэшем, но без логирования или с логированием, но без использования кэша
}