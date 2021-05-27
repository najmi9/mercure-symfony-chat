<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Infrastructure\Notification\EmailNotifierInterface;
use App\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/auth/register", name="user_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EmailNotifierInterface $emailNotifier,
    TokenGeneratorInterface $tokenGenerator, FileUploader $fileUploader): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $token = $tokenGenerator->generateToken();
            $user->setConfirmationToken($token)
                ->setIp($request->getClientIp())
            ;
            $fileName = $fileUploader->uploadUserImage($user->getFile());
            $user->setPicture($fileName);

            $email = $emailNotifier->createEmail('Email Confirmation', 'security/emails/confirm_email.html.twig', [
                'username' => $user->getName(),
                'token' => $token,
            ]);

            $adminMail = $emailNotifier->createEmail('New user created', 'emails/new_user.html.twig', [
                'user' => $user,
            ]);

            $adminMail->to($this->getParameter('admin_mail'));

            $email->to($user->getEmail());

            $emailNotifier->send($adminMail);

            $emailNotifier->send($email);

            $entityManager->persist($user);
            $entityManager->flush();

           $this->addFlash('success', 'Check Your Email To Verify Your Account.');

           return $this->redirectToRoute('user_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
