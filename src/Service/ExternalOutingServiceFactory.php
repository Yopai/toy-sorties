<?php

namespace App\Service;

use App\Entity\ExternalSource;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Exception\InvalidParameterException;
class ExternalOutingServiceFactory
{
    public function createService(ExternalSource $source): ExternalOutingServiceInterface
    {
        $className = $source->getServiceClass();
        if ( ! $className) {
            $className = DefaultOutingService::class;
        }
        if ( ! in_array(ExternalOutingServiceInterface::class, class_implements($className))) {
            throw new InvalidParameterException($className . ' doesn\'t implement ExternalOutingServiceInterface');
        }

        return new $className($source->getRootUrl(), $browser = new HttpBrowser(HttpClient::create()));
    }
}
