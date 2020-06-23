<?php

namespace App\Controller;

use App\Data\ExternalLogin;
use App\Entity\ExternalOuting;
use App\Entity\ExternalSource;
use App\Service\ExternalOutingServiceFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @param Request                      $request
     * @param SerializerInterface          $serializer
     * @param EntityManagerInterface       $em
     * @param ExternalOutingServiceFactory $factory
     *
     * @return JsonResponse
     * @Route("/api/outings", name="api.outings")
     */
    public function outings(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ExternalOutingServiceFactory $factory
    ) {
        $sources = $em->getRepository(ExternalSource::class)->findActiveIn($request->query->get('sources'));
        $outings = [];
        foreach ($sources as $source) {
            $service = $factory->createService($source);
            $outings = array_merge($outings, $service->getOutings($em, $source));
        }
        $em->flush();
        usort($outings, function(ExternalOuting $outing1, ExternalOuting $outing2) {
            return $outing1->getStartDate().' '.$outing1->getStartTime()->format('H:i') >
                   $outing2->getStartDate().' '.$outing2->getStartTime()->format('H:i');
        });

        return JsonResponse::fromJsonString($serializer->serialize($outings, 'json'));
    }

    /**
     * @param Request                      $request
     * @param SerializerInterface          $serializer
     * @param EntityManagerInterface       $em
     * @param ExternalOutingServiceFactory $factory
     *
     * @return JsonResponse
     * @Route("/api/login", name="api.login")
     */
    public function login(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ExternalOutingServiceFactory $factory
    ) {
        /** @var ExternalSource[] $sources */
        $sources = $em->getRepository(ExternalSource::class)->findActiveIn([$request->query->get('sources')]);
        $result  = [];
        foreach ($sources as $source) {
            $data                     = new ExternalLogin();
            $data->site               = $source;
            $data->login              = $request->request->get('login');
            $data->password           = $request->request->get('password');
            $service                  = $factory->createService($source);
            $result[$source->getId()] = (object)[
                'source' => $source,
                'result' => $service->login($data),
            ];
        }
        $em->flush();

        return JsonResponse::fromJsonString($serializer->serialize($result, 'json'));
    }

    /**
     * @param EntityManagerInterface $em
     *
     * @Route("/api/clear", name="api.clear")
     * @return Response
     */
    public function clear(EntityManagerInterface $em): Response
    {
        $em->getRepository(ExternalOuting::class)->deleteAll();

        return new Response('cleared');
    }

    /**
     * @param EntityManagerInterface $em
     * @param SerializerInterface    $serializer
     *
     * @return Response
     * @Route("/api/sources", name="api.sources")
     */
    public function sources(EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $sources = $em->getRepository(ExternalSource::class)->findBy(['active' => true]);

        return JsonResponse::fromJsonString($serializer->serialize($sources, 'json'));
    }

}
