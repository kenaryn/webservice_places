<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PlaceController extends AbstractController
{
    #[Route('/api/place', name: 'api_place', methods: ['GET'])]
    public function index(PlaceRepository $placeRepository, NormalizerInterface $normalizer): Response
    {
        $places = $placeRepository->findAll();
        $normalized = $normalizer->normalize($places);
        $json = json_encode($normalized);
        $response = new Response($json, RESPONSE::HTTP_OK, [
            'content-type' => 'application/json'
        ]);

        return $response;

        // return $this->render('place/index.html.twig', [
        //     'controller_name' => 'PlaceController',
        // ]);
    }

    #[Route('/api/place/{id}', name: 'api_place_with_id', methods: ['GET'])]
    public function findById(PlaceRepository $placeRepository, int $id, NormalizerInterface $normalizer): Response
    {
        $place = $placeRepository->find($id);
        $normalized = $normalizer->normalize($place);
        if ($normalized)
        $response = new Response(json_encode($normalized), RESPONSE::HTTP_OK, [
            'content-type' => 'application/json'
        ]);
        else
        $response = new Response('{"message":"No entries yet!"}', RESPONSE::HTTP_NOT_FOUND , [
            'content-type' => 'application/json'
        ]);
     

        return $response;
    }
}
