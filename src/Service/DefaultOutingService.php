<?php

namespace App\Service;

use App\Data\ExternalLogin;
use App\Entity\ExternalOuting;
use App\Entity\ExternalSource;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\BrowserKit\HttpBrowser;

abstract class DefaultOutingService implements ExternalOutingServiceInterface
{
    /** @var HttpBrowser */
    protected $browser;
    protected $rootUrl;

    public function __construct($rootUrl, $browser)
    {
        $this->browser = $browser;
        $this->rootUrl = rtrim($rootUrl, '/') . '/';
    }

    abstract public function login(ExternalLogin $data);

    abstract public function retrieveOutings(EntityManagerInterface $em, ExternalSource $source);

    abstract public function retrieveOuting(EntityManagerInterface $em, ExternalOuting $outing);

    protected function getAbsoluteUrl($source, $href)
    {
        if (strpos($href, '://') === false) {
            $href = rtrim($source->getRootUrl(), '/') . '/' . trim($href, '/');
        }

        return $href;
    }

    protected function getMonthFromName(string $month_name, $exceptionIfNotExist = true)
    {
        $month_name = iconv('UTF8', 'ASCII//TRANSLIT', strtolower($month_name));
        $months     = [
            'janvier'   => 1,
            'fevrier'   => 2,
            'mars'      => 3,
            'avril'     => 4,
            'mai'       => 5,
            'juin'      => 6,
            'juillet'   => 7,
            'juil.'     => 7,
            'aout'      => 8,
            'septembre' => 9,
            'sept.'     => 9,
            'octobre'   => 10,
            'novembre'  => 11,
            'nov.'      => 11,
            'decembre'  => 12,
            'dec.'      => 12,
        ];
        if (isset($months[$month_name])) {
            return $months[$month_name];
        }
        if ($exceptionIfNotExist) {
            throw new RuntimeException ('Mois inconnu : ' . $month_name);
        }

        return null;
    }
}
