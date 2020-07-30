<?php

namespace App\Service;

use App\Data\ExternalLogin;
use App\Entity\ExternalMember;
use App\Entity\ExternalOuting;
use App\Entity\ExternalSource;
use App\Repository\ExternalMemberRepository;
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

    public function retrieveOutings(EntityManagerInterface $em, ExternalSource $source)
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

            if ($this->isLogged()) {
                $columns = ['date', 'time', 'author', 'dept', 'virtual', 'title', 'registrations', 'waitinglist', 'actions'];
            } else {
                $columns = ['date', 'time', 'author', 'dept', 'virtual', 'title', 'actions'];
            }
            foreach ($columns as $i => $key) {
                $rowdata[$key] = trim($children->getNode($i)->textContent);
            }

            $action_column_index = array_search('actions', $columns);
            $href                = $row->filter("td:nth-child($action_column_index) a")->getNode(0)->getAttribute('href');
            preg_match('#sortie-([0-9]*)$#', $href, $matches);
            $outingId = $matches[1];

            /** @var ExternalOuting $outing */
            $outing = $em->getRepository(ExternalOuting::class)->findOrCreate($source, $outingId);
            $em->persist($outing);

            [$dow, $day, $month_name, $year] = explode(' ', $rowdata['date']);
            $date = new \DateTime();
            $date->setDate($year, $this->getMonthFromName($month_name), $day);
            $time = explode(':', $rowdata['time']);
            $date->setTime($time[0], $time[1]);
            $outing->setStartDate($date);

            $outing->setTitle($rowdata['title']);

            if (isset($rowdata['registrations'])) {
                $registrations = explode('/', $rowdata['registrations']);
                [$current, $max] = $registrations;
                $outing->setCurrentRegistrations(intval($current));
                $outing->setMaxRegistrations(intval($max));
            }

            if (isset($rowdata['waitinglist'])) {
                $outing->setWaitingRegistrations(intval($rowdata['waitinglist']));
            }

            $username = $children->getNode(array_search('author', $columns))->childNodes->item(0)->textContent;
            $outing->setAuthor($em->getRepository(ExternalMember::class)->findOneOrCreateByUsername($username));
            //$outing->setVirtual(! empty($rowdata['virtual']));
            $outing->setDepartment($rowdata['dept']);
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

    public function retrieveOuting(EntityManagerInterface $em, ExternalOuting $outing)
    {
    }

    private function isLogged()
    {
        return false;
    }
}
