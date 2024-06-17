<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Serializer;
Use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Place;

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

    #[Route('/api/place/add', name: 'api_place_add', methods: ['POST'])]
    public function addPlace(EntityManagerInterface $entityManager, NormalizerInterface $normalizer, Request $request): Response
    {
        // Retrieve JSON body.
        $jsonData = $request->getContent();

        // Normalize and decode JSON data to work with a Place class object.
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $place = $serializer->deserialize($jsonData, Place::class, 'json');

        // Save PHP object to the database.
        $entityManager->persist($place);
        $entityManager->flush();


        $normalized = $normalizer->normalize($place);
        return new Response(json_encode($normalized), RESPONSE::HTTP_CREATED, [
            'content-type' => 'application/json'
        ]);
    }
}
