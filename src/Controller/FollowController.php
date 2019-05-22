<?php

namespace App\Controller;

use App\Entity\Profile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FollowController extends AbstractController
{
    /**
     * @Route("/follow", name="profile", methods={"POST"})
     */
    public function follow(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $request    = json_decode($request->getContent(), true);
        $profileId  = $request['profile-id'];
        $followerId = $request['follower-id'];

        $profileRepo = $entityManager->getRepository(Profile::class);
        $profile     = $profileRepo->findOneById($profileId);
        $follower    = $profileRepo->findOneById($followerId);

        if (!($profile instanceof Profile)) {
            return new JsonResponse([
                'message' => 'This profile does not exist!',
            ], Response::HTTP_NOT_FOUND);
        }
        if (!($follower instanceof Profile)) {
            return new JsonResponse([
                'message' => 'This follower does not exist!',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $follower->follow($profile);
            $entityManager->persist($follower);
            $entityManager->persist($profile);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Error while saving following!',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $message = $follower->getFullName() . ' is now following ' . $profile->getFullName();

        return new JsonResponse([
            'message' => $message,
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/unfollow", name="profile", methods={"POST"})
     */
    public function unfollow(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $request    = json_decode($request->getContent(), true);
        $profileId  = $request['profile-id'];
        $followerId = $request['follower-id'];

        $profileRepo = $entityManager->getRepository(Profile::class);
        $profile     = $profileRepo->findOneById($profileId);
        $follower    = $profileRepo->findOneById($followerId);

        if (!($profile instanceof Profile)) {
            return new JsonResponse([
                'message' => 'This profile does not exist!',
            ], Response::HTTP_NOT_FOUND);
        }
        if (!($follower instanceof Profile)) {
            return new JsonResponse([
                'message' => 'This follower does not exist!',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $follower->unfollow($profile);
            $entityManager->persist($follower);
            $entityManager->persist($profile);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Error while saving unfollowing!',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $message = $follower->getFullName() . ' is now following ' . $profile->getFullName();

        return new JsonResponse([
            'message' => $message,
        ], Response::HTTP_OK);
    }
}
