<?php

namespace App\Controller;

use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class PersonController extends AbstractController
{
    #[Route('/api/person', name: 'api_person', methods: ['GET'])]
    public function index(PersonRepository $personRepository, NormalizerInterface $normalizer): Response
    {
        $persons = $personRepository->findAll();
        $normalized = $normalizer->normalize($persons);
        $jsonData = json_encode($normalized);

        $response = new Response($jsonData, RESPONSE::HTTP_OK, [
            'content-type' => 'application/json'
        ]);

        return $response;
    }

    #[Route('/api/person/{id}', name: 'api_person_with_id', methods: ['GET'])]
    public function findById(PersonRepository $personRepository, NormalizerInterface $normalizer, int $id, SerializerInterface $serializer): Response
    {
        $person = $personRepository->find($id);
        $ctx = (new ObjectNormalizerContextBuilder())
            ->withGroups('show_person')
            ->toArray();
            
        $normalized = $normalizer->normalize($person);
        $jsonData = json_encode($normalized);
        $jsonSerialized = $serializer->serialize($jsonData, 'json', $ctx);

        $response = new Response($jsonSerialized, RESPONSE::HTTP_OK, [
            'content-type' => 'application/json'
        ]);

        return $response;
    }
}