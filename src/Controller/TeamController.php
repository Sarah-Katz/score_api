<?php

namespace App\Controller;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class TeamController extends AbstractController
{
    #[Route('/teams', name: 'app_team_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $teams = $entityManager->getRepository(Team::class)->findAll();

        $normalizedTeams = $serializer->normalize($teams, null, [
            AbstractNormalizer::GROUPS                     => ['team:read'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($normalizedTeams);
    }

    #[Route('/teams', name: 'app_team_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $team = new Team();
        $team->setName($data['name']);

        $entityManager->persist($team);
        $entityManager->flush();

        return $this->json($team, 201);
    }

    #[Route('/teams/{id}', name: 'app_team_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $team = $entityManager->getRepository(Team::class)->find($id);

        if (!$team) {
            return $this->json(['message' => 'Team not found'], 404);
        }

        $normalizedTeam = $serializer->normalize($team, null, [
            AbstractNormalizer::GROUPS                     => ['team:read'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($normalizedTeam, 200);
    }

    #[Route('/teams/{id}', name: 'app_team_update', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $team = $entityManager->getRepository(Team::class)->find($id);

        if (!$team) {
            return $this->json(['message' => 'Team not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $team->setName($data['name']);

        $entityManager->flush();

        $normalizedTeam = $serializer->normalize($team, null, [
            AbstractNormalizer::GROUPS                     => ['team:read'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($normalizedTeam, 200);
    }

    #[Route('/teams/{id}', name: 'app_team_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $team = $entityManager->getRepository(Team::class)->find($id);

        if (!$team) {
            return $this->json(['message' => 'Team not found'], 404);
        }

        $entityManager->remove($team);
        $entityManager->flush();

        return $this->json(['message' => 'Team deleted successfully'], 200);
    }
}
