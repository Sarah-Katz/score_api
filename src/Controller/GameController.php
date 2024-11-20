<?php

namespace App\Controller;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class GameController extends AbstractController
{
    private const GAME_ROUTE = '/games/{id}';
    private const GAME_NOT_FOUND = 'Game not found';

    #[Route('/games', name: 'app_game_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $games = $entityManager->getRepository(Game::class)->findAll();

        $normalizedGames = $serializer->normalize($games, null, [
            AbstractNormalizer::GROUPS                     => ['game:read'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($normalizedGames);
    }

    #[Route('/games', name: 'app_game_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $game = new Game();

        $entityManager->persist($game);
        $entityManager->flush();

        $normalizedGame = $serializer->normalize($game, null, [
            AbstractNormalizer::GROUPS                     => ['game:read'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($normalizedGame, 201);
    }

    #[Route(self::GAME_ROUTE, name: 'app_game_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $game = $entityManager->getRepository(Game::class)->find($id);

        if (!$game) {
            return $this->json(['message' => self::GAME_NOT_FOUND], 404);
        }

        $normalizedGame = $serializer->normalize($game, null, [
            AbstractNormalizer::GROUPS                     => ['game:read'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($normalizedGame);
    }

    #[Route(self::GAME_ROUTE, name: 'app_game_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $game = $entityManager->getRepository(Game::class)->find($id);

        if (!$game) {
            return $this->json(['message' => self::GAME_NOT_FOUND], 404);
        }

        $entityManager->remove($game);
        $entityManager->flush();

        return $this->json(['message' => 'Game deleted successfully']);
    }
}