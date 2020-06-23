<?php

namespace App\Service;

use App\Data\ExternalLogin;
use App\Entity\ExternalOuting;
use App\Entity\ExternalSource;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class TMSOutingService extends DefaultOutingService implements ExternalOutingServiceInterface
{
    const VIEW_ALL_ROUTE = 'sorties-site-TMS';
    const LOGIN_ROUTE    = 'connexion.php';

    public function login(ExternalLogin $data)
    {
        // TODO
        $this->browser->request('GET', $this->rootUrl . self::LOGIN_ROUTE);
        $this->browser->submitForm('button_173257', [
            'username'      => '',
            'pseudo'        => $data->login,
            'mot_de_passe'  => $data->password,
            'p'             => '',
            's'             => '',
            'm'             => '',
            'valider'       => 'oui',
            'button_173257' => '',
        ]);
    }

    public function getOutings(EntityManagerInterface $em, ExternalSource $source)
    {
        $this->browser->request('GET', $this->rootUrl . self::VIEW_ALL_ROUTE);
        $crawler = $this->browser->getCrawler();

        $table   = $crawler->filter('#datatable1');
        $outings = [];

        $table->filter('tbody tr')->each(function (Crawler $row) use (&$outings, $em, $source) {
            $children = $row->children();
            if ( ! $children->getNode(1)->textContent) {
                // ignore "week" rows
                return;
            }

            $href = $row->filter('td:nth-child(6) a')->getNode(0)->getAttribute('href');

            [$dow, $day, $month_name, $year] = explode(' ', $children->getNode(0)->textContent);
            $date = new \DateTime();
            $date->setDate($year, $this->getMonthFromName($month_name), $day);
            $time = explode(':', $children->getNode(1)->textContent);
            $date->setTime($time[0], $time[1]);
            [$current, $max] = explode('/', $children->getNode(6)->textContent);

            preg_match('#sortie-([0-9]*)$#', $href, $matches);
            $outing = $em->getRepository(ExternalOuting::class)->findOrCreate($source, $matches[1]);
            $em->persist($outing);
            $outing->setStartDate($date->format('Y-m-d'));
            $outing->setStartTime($date);
            $outing->setTitle($children->getNode(5)->textContent);
            $outing->setCurrentRegistrations(intval($current));
            $outing->setMaxRegistrations(intval($max));
            $outing->setWaitingRegistrations(intval($children->getNode(7)->textContent));
            $outing->setAuthor($children->getNode(2)->childNodes->item(0)->textContent);
            $outing->setVirtual(! empty(trim($children->getNode(4)->textContent)));
            $outing->setDepartment($children->getNode(3)->textContent);
            $outing->setExternalUrl($this->getAbsoluteUrl($source, $href));

            $outings [] = $outing;
        })
        ;

        /*
                # check if logged in
                $btn = $crawler->filter('#btnconnect1');
                if ($btn) {
                    // not logged
                }
        */

        return $outings;
    }

    public function getOuting(EntityManagerInterface $em, ExternalOuting $outing)
    {
    }
}
