<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/api/v1/movie', name: 'movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie', methods: ['GET', 'HEAD'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/MovieController.php',
        ]);
    }

    #[Route("", name: "create_movie", methods: ["POST"])]
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title']) || !isset($data['desc'])) {
            return new Response("Missing parameters", 422);
        }

        $movie = new Movie();
        $movie->setTitle($data['title']);
        $movie->setDescription($data['desc']);
        $entityManager->persist($movie);
        $entityManager->flush();

        return new Response('Saved new movie with id ' . $movie->getId());
    }
}
