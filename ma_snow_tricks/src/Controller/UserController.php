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
use App\Form\Type\PasswordRequestType;
use App\Form\Type\ChangePasswordType;
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
            $subject = 'Confirmation d\'inscription';
            $content = '<h5>Cliquez sur le lien suivant pour valider votre inscription : </h5><a href="'.$confirmRegistrationPage.'">Confirmer</a>';
            $this->sendEmail($user->getEmail(), $subject, $content, $mailer);
            // Send Email End

            $entityManager->flush($user);

            $this->addFlash(
                'success',
                'Un email vous a été envoyé pour valider votre compte',
            );

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

        $this->addFlash(
            'success',
            'Votre compte a bien été validé',
        );

        return $this->redirectToRoute('app_login');
    }

    #[Route('/account', name: 'account')]
    public function userAccount(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->addFlash(
            'success',
            'Vous êtes bien connecté',
        );

        $user = $this->getUser();

        return $this->render('user/account.html.twig');
    }

    #[Route('/change-password-request', name: 'change_password_request')]
    public function changePasswordRequest(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $form = $this->createForm(PasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData();
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email, 'statut' => 1]);

            if (!$user) {
                throw $this->createNotFoundException(
                    'Ce compte n\'existe pas'
                );
            }

            $user->setResetPasswordToken(true);
            $entityManager->flush();

            $changePasswordUrl = $this->generateUrl('change_password', [
                'reset_password_token' => $user->getResetPasswordToken(),
            ]);
            
            // Send Email Start
            $subject = 'Changer votre mot de passe';
            $content = '<h5>Cliquez sur le lien suivant pour changer votre mot de passe : </h5><a href="'.$changePasswordUrl.'">Changer mon mot de passe</a>';
            $this->sendEmail($user->getEmail(), $subject, $content, $mailer);
            // Send Email End

            $this->addFlash(
                'success',
                'Un email vous a été envoyé pour changer votre mot de passe',
            );
        }
        
        return $this->render('user/changePasswordRequest.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/change-password/{reset_password_token}', name: 'change_password')]
    public function changePassword(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        if (!$user) {
            throw $this->createNotFoundException(
                'Ce compte n\'existe pas'
            );
        }

        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword(),
            );
            $user->setPassword($hashedPassword);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre mot de passe a bien été changé',
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/changePassword.html.twig', [
            'form' => $form,
        ]);
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

    public function sendEmail(String $userEmail, String $subject, String $content, MailerInterface $mailer): void {
        $email = (new Email())
        ->to($userEmail)
        ->subject($subject)
        ->html($content);
        $mailer->send($email);
    }
}
