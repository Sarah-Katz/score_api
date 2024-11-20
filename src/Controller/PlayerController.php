<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class PlayerController extends AbstractController
{
    #[Route('/players', name: 'app_player_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $players = $entityManager->getRepository(Player::class)->findAll();

        $normalizedPlayers = $serializer->normalize($players, null, [
            AbstractNormalizer::GROUPS                     => ['player:read'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($normalizedPlayers);
    }

    #[Route('/players', name: 'app_player_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $player = new Player();
        $player->setName($data['name']);
        $team = $entityManager->getRepository(Team::class)->find($data['team']);
        $player->setTeam($team);

        $entityManager->persist($player);
        $entityManager->flush();

        $normalizedPlayer = $serializer->normalize($player, null, [
            AbstractNormalizer::GROUPS                     => ['player:read'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($normalizedPlayer, 201);
    }

    #[Route('/players/{id}', name: 'app_player_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $player = $entityManager->getRepository(Player::class)->find($id);

        if (!$player) {
            return $this->json(['message' => 'Player not found'], 404);
        }

        $normalizedPlayer = $serializer->normalize($player, null, [
            AbstractNormalizer::GROUPS                     => ['player:read'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($normalizedPlayer, 200);
    }

    #[Route('/players/{id}', name: 'app_player_update', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $player = $entityManager->getRepository(Player::class)->find($id);

        if (!$player) {
            return $this->json(['message' => 'Player not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $player->setName($data['name']);
        $team = $entityManager->getRepository(Team::class)->find($data['team']);
        $player->setTeam($team);

        $entityManager->flush();

        $normalizedPlayer = $serializer->normalize($player, null, [
            AbstractNormalizer::GROUPS                     => ['player:read'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($normalizedPlayer, 200);
    }

    #[Route('/players/{id}', name: 'app_player_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $player = $entityManager->getRepository(Player::class)->find($id);

        if (!$player) {
            return $this->json(['message' => 'Player not found'], 404);
        }

        $entityManager->remove($player);
        $entityManager->flush();

        return $this->json(['message' => 'Player deleted successfully'], 200);
    }
}
