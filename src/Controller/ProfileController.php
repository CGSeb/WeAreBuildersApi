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

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile", methods={"POST"})
     */
    public function createProfile(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $request    = json_decode($request->getContent(), true);
        $firstName  = $request['first-name'];
        $lastName   = $request['last-name'];
        $email      = $request['email'];
        $birthDate  = $request['birth-date'];
        $newProfile = $this->createNewProfile($firstName, $lastName, $email, $birthDate);
        $errors     = $validator->validate($newProfile);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse([
                'message' => $errorsString,
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $entityManager->persist($newProfile);
            $entityManager->flush();
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'Error while saving profile!',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $message = 'Profile: ' . $newProfile->getFullName() . ' created';

        return new JsonResponse([
            'message' => $message,
        ], Response::HTTP_CREATED);
    }

    /**
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $birthDate
     * @return Profile
     */
    private function createNewProfile($firstName, $lastName, $email, $birthDate): Profile
    {
        $newProfile = new Profile();
        $newProfile->setFirstName($firstName);
        $newProfile->setLastName($lastName);
        $newProfile->setEmail($email);
        $newProfile->setBirthDate($birthDate);

        return $newProfile;
    }
}
