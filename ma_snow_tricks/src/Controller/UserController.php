<?php

// src/Controller/UserController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\Type\UserType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Image;

class UserController extends AbstractController
{
    #[Route('/registration')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer, SluggerInterface $slugger): Response
    {
        // Check if user is logged Start
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('account');
        }
        // Check if user is logged End

        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword(),
            );
            $user->setPassword($hashedPassword);
            $user->setRegistrationToken(true);
            $user->setStatut(0);

            // Upload Image Start
            $image = $form->get('image')->getData();
            if ($image) {
                $newFilename = $this->uploadUserImage($image, $slugger);
                $user->setImage($newFilename);
            }
            // Upload Image End

            $entityManager->persist($user);
            $confirmRegistrationPage = $this->generateUrl('confirm_registration', [
                'registration_token' => $user->getRegistrationToken(),
            ]);

            // Send Email Start
            $this->sendEmailRegistration($user->getEmail(), $confirmRegistrationPage, $mailer);
            // Send Email End

            $entityManager->flush($user);
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/registration.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/confirm-registration/{registration_token}', name: 'confirm_registration')]
    public function confirmRegistration(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        if (!$user) {
            throw $this->createNotFoundException(
                'Aucun utilisateur trouvé',
            );
        }
        $user->setRegistrationToken(false);
        $user->setStatut(1);
        $entityManager->flush();

        return $this->redirectToRoute('app_login');
    }

    #[Route('/account', name: 'account')]
    public function userAccount(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        return $this->render('user/account.html.twig');
    }

    public function uploadUserImage(Image $image, SluggerInterface $slugger): string {
        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
        $image->move(
            $this->getParameter('upload_directory'),
            $newFilename
        );
        return $newFilename;
    }

    public function sendEmailRegistration(String $userEmail, String $confirmRegistrationPage, MailerInterface $mailer): void {
        $email = (new Email())
        ->to($userEmail)
        ->subject('Confirmation d\'inscription')
        ->html('
                <h5>Cliquez sur le lien suivant pour valider votre inscription : </h5>
                <a href="'.$confirmRegistrationPage.'">Confirmer</a>
              ');
        $mailer->send($email);
    }
}
