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

    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('', name: 'initial_movie', methods: ['GET', 'HEAD'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/MovieController.php',
        ]);
    }

    #[Route('/{id}', name: 'getById_movie', methods: ['GET'])]
    public function getMovieById(int $id): Response
    {
        $movie = $this->em->getRepository(Movie::class)->find($id);

        if (!$movie) return new Response("No movie with id: " . $id . " found", 404);

        return new JsonResponse($movie->asArray(), 200);
    }


    #[Route('/update', name: 'update_movie', methods: ['PUT'])]
    public function updateMovie(Request $request): Response
    {
        $requestJson = json_decode($request->getContent(), true);

        if (!isset($requestJson['id'])) return new Response("Movie id must be present");

        if (!isset($requestJson['movie'])) return new Response("No movie data to update");

        $movieData = $requestJson['movie'];

        $movie = $this->em->getRepository(Movie::class)->find($requestJson['id']);

        if (!isset($movie)) return new Response("No movie with id: " . $requestJson['id'] . " found");

        if (isset($movieData['title'])) $movie->setTitle($movieData['title']);

        if (isset($movieData['desc'])) $movie->setDescription($movieData['desc']);

        $this->em->flush();

        return new Response("put: " . $movieData['title']);
    }




    #[Route("", name: "create_movie", methods: ["POST"])]
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title']) || !isset($data['desc'])) return new Response("Missing parameters", 422);

        $movie = new Movie();
        $movie->setTitle($data['title']);
        $movie->setDescription($data['desc']);
        $this->em->persist($movie);
        $this->em->flush();

        return new Response('Saved new movie with id ' . $movie->getId());
    }
}
