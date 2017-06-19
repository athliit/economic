<?php

namespace Athliit\Economic;

use Athliit\Economic\Economic;

class EconomicServiceFactory
{
    /**
     * Services path
     *
     * @var string
     */
    const SERVICES_NAMESPACE = '\Athliit\Economic\Services';

    /**
     * Service
     */
    static protected $service;

    /**
     * Create Economic Service
     *
     * @param string $service
     * @param \Athliit\Economic\Economic $manager
     */
    public static function create($service, Economic $manager)
    {
        self::$service = $service;

        $class = self::SERVICES_NAMESPACE . "\\". self::getFolder() . "\\" . self::getClass();

        return new $class($manager);
    }

    /**
     * Get key based on service name
     *
     * @param string $service
     *
     * @return string
     */
    public static function getKey($service)
    {
        $parts = self::getServiceParts($service);

        return strtolower($parts['folder'] . "." . $parts['service']);
    }

    /**
     * Gets the folder.
     *
     * @return string
     */
    protected static function getFolder()
    {
        $parts = self::getServiceParts(self::$service);

        return $parts['folder'];
    }

    /**
     * Gets the class.
     *
     * @return string
     */
    protected static function getClass()
    {
        $parts = self::getServiceParts(self::$service);

        return $parts['service'];
    }

    protected static function getServiceParts($service)
    {
        $parts = explode('.', $service);
        $folder = ucfirst($parts[0]);
        $service = isset($parts[1]) ? ucfirst($parts[1]) : ucfirst($parts[0]);

        if (isset($parts[2])) {
            $service = $service . ucfirst($parts[2]);
        }

        return ['folder' => $folder, 'service' => $service];
    }
}
