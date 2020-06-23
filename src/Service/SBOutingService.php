<?php

namespace App\Service;

use App\Data\ExternalLogin;
use App\Entity\ExternalOuting;
use App\Entity\ExternalSource;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class SBOutingService extends DefaultOutingService implements ExternalOutingServiceInterface
{
    const VIEW_ALL_ROUTE = 'index.php?sorties=prevues';
    const LOGIN_ROUTE    = 'connexion.php';

    private $logged = false;

    public function login(ExternalLogin $data)
    {
        $this->browser->request('GET', $this->rootUrl . self::LOGIN_ROUTE);

        $this->browser->submitForm('', [
            'pseudo'          => $data->login,
            'motdepasseperso' => $data->password,
            'validation'      => 'Ok',
            'memo'            => 'ON',
        ]);
    }

    public function getOutings(EntityManagerInterface $em, ExternalSource $source)
    {
        $this->browser->request('GET', $this->rootUrl . self::VIEW_ALL_ROUTE);
        $crawler = $this->browser->getCrawler();

        $table   = $crawler->filter('.affichage > table');
        $outings = [];
        $currentDate = [
            'year' => date('Y'),
            'month' => date('m'),
        ];

        $table->children('[id^=commentaire]')->each(function (Crawler $row) use (&$currentDate, &$outings, &$first, $em, $source) {
            $childrenCount = $row->filter('td')->count();
            $nodeContent = $row->filter('td')->getNode(0)->textContent;
            if ($childrenCount == 2) {
                $month = $this->getMonthFromName($nodeContent, false);
                if ($month) {
                    if ($month < $currentDate['month']) {
                        $currentDate['year']++;
                    }
                    $currentDate['month'] = $month;
                }
                else {
                    if (!$nodeContent) {
                        return;
                    }
                    // nbsp ?
                    $nodeContent = str_replace(' ', ' ', $nodeContent);
                    [$dow, $day] = explode(' ', $nodeContent);
                    $currentDate['day'] = $day;
                }
            }
            else {
                $rowdata = [];
                foreach ($row->children()->getIterator() as $column) {
                    $rowdata[] = trim($column->textContent);
                }
                $href = $row->filter('a')->getNode(2)->getAttribute('href');
                if (!$this->isLogged()) {
                    if (strpos($href, '://') !== false) {
                        // ignore outings from other SB cities
                        return;
                    }
                }
                preg_match('#sortie_n=([0-9]*)#', $href, $matches);
                $outing = $em->getRepository(ExternalOuting::class)->findOrCreate($source, $matches[1]);
                $em->persist($outing);

                $time = explode(':', $rowdata[0]);
                $date = new DateTime();
                $date->setDate($currentDate['year'], $currentDate['month'], $currentDate['day']);
                $date->setTime($time[0], $time[1]);
                $outing->setExternalUrl($this->getAbsoluteUrl($source, $href));

                $outing->setStartDate($date->format('Y-m-d'));
                $outing->setStartTime($date);
                $outing->setTitle($rowdata[4]);
                if ($rowdata[3]) {
                    [$current, $max] = explode(' / ', $rowdata[3]);
                    $outing->setCurrentRegistrations(intval($current));
                    $outing->setMaxRegistrations(intval($max));
                }
                if ($this->isLogged()) {
                    $outing->setAuthor($rowdata[1]);
                }
                else {
                    $outing->setAuthor('');
                }
                $outings [] = $outing;
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

    /**
     * @return bool
     */
    public function isLogged(): bool
    {
        return $this->logged;
    }
}
