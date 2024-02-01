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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Image;
use App\Service\EmailService;
use App\Service\RegistrationService;
use App\Service\ConfirmUserService;
use App\Service\TokenService;
use App\Service\ChangePasswordService;

class UserController extends AbstractController
{
    public function __construct(
        private RegistrationService $registrationService,
        private ConfirmUserService $confirmUserService,
        private EmailService $emailService,
        private TokenService $tokenService,
        private ChangePasswordService $changePasswordService,
    ) {
    }

    #[Route('/registration')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
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
            $this->registrationService->userRegister($user);
            $entityManager->persist($user);
            $entityManager->flush($user);

            $this->addFlash(
                'success',
                'Un email vous a été envoyé pour valider votre compte',
            );
            
            $confirmRegistrationPage = $this->generateUrl('confirm_registration', ['registrationToken' => $user->getRegistrationToken(),]);
            $this->emailService->sendEmail($user->getEmail(), 'Confirmation d\'inscription', '<h5>Cliquez sur le lien suivant pour valider votre inscription : </h5><a href="'.$confirmRegistrationPage.'">Confirmer</a>');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/registration.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/confirm-registration/{registrationToken}', name: 'confirm_registration')]
    public function confirmRegistration(EntityManagerInterface $entityManager, User $user): Response
    {
        $this->confirmUserService->userConfirm($user);
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
    public function changePasswordRequest(Request $request, EntityManagerInterface $entityManager): Response
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

            $user->generateResetPasswordToken();
            $user->setResetPasswordTokenDate();
            $entityManager->flush();

            $changePasswordUrl = $this->generateUrl('change_password', [
                'resetPasswordToken' => $user->getResetPasswordToken(),
            ]);

            $this->emailService->sendEmail($user->getEmail(), 'Changer votre mot de passe', '<h5>Cliquez sur le lien suivant pour changer votre mot de passe : </h5><a href="'.$changePasswordUrl.'">Changer mon mot de passe</a>');

            $this->addFlash(
                'success',
                'Un email vous a été envoyé pour changer votre mot de passe',
            );
        }
        
        return $this->render('user/changePasswordRequest.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/change-password/{resetPasswordToken}', name: 'change_password')]
    public function changePassword(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if (!$this->tokenService->checkValidationToken($user->getResetPasswordTokenDate())) {
            $this->addFlash(
                'danger',
                'Le token à expiré, veuillez recommencer',
            );
            return $this->redirectToRoute('change_password_request');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->changePasswordService->changePassword($user);
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
}
