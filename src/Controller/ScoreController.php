<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Score;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ScoreController extends AbstractController
{
    private const SCORE_ROUTE = '/scores/{id}';
    private const SCORE_NOT_FOUND = 'Score not found';

    #[Route('/scores', name: 'app_score_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $scores = $entityManager->getRepository(Score::class)->findAll();

        $normalizedScores = $serializer->normalize($scores, null, [
            AbstractNormalizer::GROUPS                     => ['score:read'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($normalizedScores);
    }

    #[Route('/scores', name: 'app_score_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $score = new Score();
        $score->setValue($data['value']);
        $game = $entityManager->getRepository(Game::class)->find($data['game']);
        $score->setGame($game);
        $team = $entityManager->getRepository(Team::class)->find($data['team']);
        $score->setTeam($team);

        $entityManager->persist($score);
        $entityManager->flush();

        $normalizedScore = $serializer->normalize($score, null, [
            AbstractNormalizer::GROUPS                     => ['score:read'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($normalizedScore, 201);
    }

    #[Route(self::SCORE_ROUTE, name: 'app_score_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $score = $entityManager->getRepository(Score::class)->find($id);

        if (!$score) {
            return $this->json(['message' => self::SCORE_NOT_FOUND], 404);
        }

        $normalizedScore = $serializer->normalize($score, null, [
            AbstractNormalizer::GROUPS                     => ['score:read'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($normalizedScore);
    }

    #[Route(self::SCORE_ROUTE, name: 'app_score_update', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $score = $entityManager->getRepository(Score::class)->find($id);

        if (!$score) {
            return $this->json(['message' => self::SCORE_NOT_FOUND], 404);
        }

        $data = json_decode($request->getContent(), true);
        $game = $entityManager->getRepository(Game::class)->find($data['game']);
        $score->setGame($game);
        $team = $entityManager->getRepository(Team::class)->find($data['team']);
        $score->setTeam($team);

        $entityManager->flush();

        $normalizedScore = $serializer->normalize($score, null, [
            AbstractNormalizer::GROUPS                     => ['score:read'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return $this->json($normalizedScore);
    }

    #[Route(self::SCORE_ROUTE, name: 'app_score_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $score = $entityManager->getRepository(Score::class)->find($id);

        if (!$score) {
            return $this->json(['message' => self::SCORE_NOT_FOUND], 404);
        }

        $entityManager->remove($score);
        $entityManager->flush();

        return $this->json(['message' => 'Score deleted successfully']);
    }
}
