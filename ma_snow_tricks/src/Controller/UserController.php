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
use App\Service\CheckTokenService;
use App\Service\ChangePasswordService;
use App\Service\ChangePasswordRequestService;
use App\Service\RegenerateTokenService;

class UserController extends AbstractController
{
    #[Route('/registration')]
    public function index(Request $request, RegistrationService $registrationService): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('account');
        }

        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $registrationService->userRegister($user);

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

    #[Route('/confirm-registration/{registrationToken}', name: 'confirm_registration')]
    public function confirmRegistration(User $user, ConfirmUserService $confirmUserService, CheckTokenService $checkTokenService): Response
    {
        if (!$checkTokenService->checkValidationToken($user->getRegistrationTokenDate())) {
            return $this->redirectToRoute('regenerate_token_request', ['token' => $user->getRegistrationToken()]);
        }

        $confirmUserService->userConfirm($user);

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
    public function changePasswordRequest(Request $request, ChangePasswordRequestService $changePasswordRequestService): Response
    {
        $form = $this->createForm(PasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData();
            $changePasswordRequestService->requestChangePassword($email);

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
    public function changePassword(Request $request, User $user, CheckTokenService $checkTokenService, ChangePasswordService $changePasswordService): Response
    {
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if (!$checkTokenService->checkValidationToken($user->getResetPasswordTokenDate())) {
            return $this->redirectToRoute('regenerate_token_request', ['token' => $user->getResetPasswordToken()]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $changePasswordService->changePassword($user);

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

    #[Route('/regenerate-token-request/{token}', name: 'regenerate_token_request')]
    public function regenerateTokenRequest(string $token): Response
    {
       return $this->render('token/errorToken.html.twig', ['token' => $token]);
    }

    #[Route('/regenerate-token/{token}', name: 'regenerate_token')]
    public function regenerateToken(RegenerateTokenService $regenerateTokenService, string $token): Response
    {
        $regenerateTokenService->regenerateToken($token);
        $this->addFlash(
            'success',
            'Un nouveau token vous a été envoyé par email',
        );
        return $this->redirectToRoute('app_login');
    }
}
