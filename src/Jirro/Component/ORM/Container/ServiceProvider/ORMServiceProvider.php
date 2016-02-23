<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\ORM\Container\ServiceProvider;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager as ObjectManager;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validation\Constraints;

class ORMServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'service.orm.object_manager',
        'service.orm.validator',
    ];

    public function register()
    {
        $this->container->add(
            'service.orm.object_manager',
            function () {
                $development = false;
                if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
                    $development = true;
                }

                $config = new Configuration();

                if ($development) {
                    $cache = new ArrayCache();
                } else {
                    $cache = new ApcCache();
                }
                $config->setMetadataCacheImpl($cache);

                $mappings = $this->container->get('config')['orm']['mappings'];
                $config->setMetadataDriverImpl(new XmlDriver($mappings));

                $proxyDir = $this->container->get('config')['orm']['proxy_dir'];
                $config->setProxyDir($proxyDir);

                $config->setQueryCacheImpl($cache);
                $config->setProxyNamespace('Jirro\ORM\Proxies');

                if ($development) {
                    $config->setAutoGenerateProxyClasses(true);
                } else {
                    $config->setAutoGenerateProxyClasses(false);
                }

                $connection    = $this->container->get('service.dbal.connection');
                $objectManager = ObjectManager::create($connection, $config);

                return $objectManager;
            }
        );

        $this->container->add(
            'service.orm.validator',
             function () {
                $validatorBuilder = Validation::createValidatorBuilder();

                $validators = $this->container->get('config')['orm']['validators'];
                if ($validators) {
                    $validatorBuilder->addXmlMappings($validators);
                }

                $validator = $validatorBuilder->getValidator();

                return $validator;
            }
        );
    }
}
