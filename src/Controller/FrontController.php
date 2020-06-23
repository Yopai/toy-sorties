<?php

namespace App\Controller;

use App\Data\ExternalLogin;
use App\Entity\ExternalOuting;
use App\Entity\Outing;
use App\Forms\ExternalLoginFormType;
use App\Service\OVSOutingService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
class FrontController extends AbstractController
{

    /**
     * @param Request $request
     * @Route("/", name="home")
     */
    public function home(Request $request, EntityManagerInterface $em, SessionInterface $session)
    {
        return $this->render('index.html.twig');
    }

    /**
     * @param Request $request
     * @Route("/outing/{id}", name="outing")
     * @ParamConverter("outing", class="Outing")
     */
    public function outing(Request $request, EntityManagerInterface $em, Outing $outing)
    {
        return $this->render('outing.html.twig', [
            'outing' => $outing,
        ]);
    }

    /**
     * @param Request $request
     * @Route("/external/login", name="external.login")
     */
    public function external_login(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(ExternalLoginFormType::class, new ExternalLogin);
        $form->handleRequest($request);
        $outings = null;
        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            $data    = $form->getData();
            $outings = $this->queryOutings($data);
        }

        return $this->render('external/login.html.twig', [
            'form'    => $form->createView(),
            'outings' => $outings,
        ]);
    }

    private function queryOutings($data)
    {
        $outings = $this->_queryOutings($data);

        /**
         * If we are not not logged, the above call retrieved "anonymous" outings
         * So we try to log in, and get all the outings
         **/
        if ( ! $data->sessionId && $this->_login($data)) {
            $outings += $this->_queryOutings($data);
        }
    }

    private function _login(ExternalLogin $data)
    {
        // TODO : test if login successful !
        if (true) {
            $data->sessionId = $browser->getCookieJar()->get('PHPSESSID');
        } else {
            return false;
        }
    }
}
