<?php

namespace App\Service;

use App\Data\ExternalLogin;
use App\Entity\ExternalOuting;
use App\Entity\ExternalSource;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;

class OVSOutingService extends DefaultOutingService implements ExternalOutingServiceInterface
{
    const VIEW_ALL_ROUTE = 'vue_sortie_all.php';

    public function login(ExternalLogin $data)
    {
        $this->browser->request('GET', $this->rootUrl);
        $this->browser->submitForm('btnconnect1', [
            'Pseudo'   => $data->login,
            'token2'   => $data->login,
            'Password' => $data->password,
        ]);
    }

    public function getOutings(EntityManagerInterface $em, ExternalSource $source)
    {
        $this->browser->request('GET', $this->rootUrl . self::VIEW_ALL_ROUTE);
        $crawler = $this->browser->getCrawler();

        $table       = $crawler->filter('.encadrage');
        $outings     = [];
        $currentDate = [];

        $table->children()->slice(1)->each(function (Crawler $row) use (&$currentDate, &$outings, &$first, $em, $source) {
            $node = $row->getNode(0);
            switch (trim($node->getAttribute('class'))) {
                case 'Event_LineMois':
                    [$month, $year] = explode(' ', $node->textContent);
                    $currentDate = [
                        'year'  => intval($year),
                        'month' => intval($this->getMonthFromName($month)),
                        'day'   => null,
                    ];
                break;
                case 'Event_LineJour':
                    [$dow, $day] = explode(' ', $node->textContent);
                    $currentDate['day'] = intval($day);
                break;
                case 'Event_Line':
                    $rowdata = [];
                    foreach ($row->children()->filter('[class^=Event]')->getIterator() as $column) {
                        $rowdata[] = $column->textContent;
                    }
                    $href = $row->filter('a')->getNode(0)->getAttribute('href');
                    preg_match('#-([0-9]*)\.html#', $href, $matches);
                    $outing = $em->getRepository(ExternalOuting::class)->findOrCreate($source, $matches[1]);
                    $em->persist($outing);
                    $time = explode(':', $rowdata[1]);
                    $date = new DateTime();
                    $date->setDate($currentDate['year'], $currentDate['month'], $currentDate['day']);
                    $date->setTime($time[0], $time[1]);

                    $outing->setExternalUrl($this->getAbsoluteUrl($source, $href));
                    $outing->setStartDate($date->format('Y-m-d'));
                    $outing->setStartTime($date);
                    $outing->setTitle($rowdata[2]);
                    [$current, $max] = explode(' / ', $rowdata[3]);
                    $outing->setCurrentRegistrations(intval($current));
                    $outing->setMaxRegistrations(intval($max));
                    $outing->setAuthor($rowdata[4]);
                    $outings [] = $outing;
                break;
            }
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
