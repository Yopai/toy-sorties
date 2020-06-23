<?php

namespace App\Service;

use App\Data\ExternalLogin;
use App\Entity\ExternalOuting;
use App\Entity\ExternalSource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
interface ExternalOutingServiceInterface
{
    public function __construct(string $rootUrl, HttpBrowser $browser);
    public function login(ExternalLogin $data);
    public function getOutings(EntityManagerInterface $em, ExternalSource $source);
    public function getOuting(EntityManagerInterface $em, ExternalOuting $outing);
}
