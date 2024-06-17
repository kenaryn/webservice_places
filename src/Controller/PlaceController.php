<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlaceController extends AbstractController
{
    #[Route('/api/place', name: 'api_place', methods: ['GET'])]
    public function index(PlaceRepository $placeRepository): Response
    {
        $places = $placeRepository->findAll();
        $json = json_encode($places);
        $response = new Response($json, RESPONSE::HTTP_OK, [
            'content-type' => 'application/json'
        ]);

        return $response;

        // return $this->render('place/index.html.twig', [
        //     'controller_name' => 'PlaceController',
        // ]);
    }

    #[Route('/api/place/{id}', name: 'api_place_with_id', methods: ['GET'])]
    public function findById(PlaceRepository $placeRepository, int $id): Response
    {
        $place = $placeRepository->find($id);
        if ($place)
        $response = new Response(json_encode($place), RESPONSE::HTTP_OK, [
            'content-type' => 'application/json'
        ]);
        else
        $response = new Response('No entries yet!', RESPONSE::HTTP_NOT_FOUND , [
            'content-type' => 'application/json'
        ]);
     

        return $response;
    }
}
