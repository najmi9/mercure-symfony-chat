<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Auth\EmailFormType;
use App\Form\Auth\ResetPasswordType;
use App\Infrastructure\Notification\EmailNotifierInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/auth", name="user_")
 */
class SecurityController extends AbstractController
{
    /**
     *@Route("/login", name="login", options={"expose": true}, methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        if ($this->getUser() && 1 !== $this->getUser()->getId()) {
            return $this->redirectToRoute('home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $email = $request->query->get('email', null);

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername ?? $email, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/reset-password/send-email", name="send_mail", methods={"GET", "POST"})
     */
    public function sendEmail(Request $request, UserRepository $userRepo, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $em,
    EmailNotifierInterface $emailNotifier): Response
    {
        $form = $this->createForm(EmailFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $to = $form->get('email')->getData();

            /** @var User $user */
            $user = $userRepo->findOneBy(['email' => $to]);
            if (!empty($user)) {
                //generate a token
                $token = $tokenGenerator->generateToken();
                $user->setConfirmationToken($token);
                $em->persist($user);
                $em->flush();

                $subject = 'Forgot Password Request';

                $email = $emailNotifier->createEmail($subject, 'security/emails/send_email.html.twig', [
                    'username' => $user->getName(),
                    'token' => $token,
                ]);
                $email->to($to);
                $emailNotifier->send($email);
                $this->addFlash('success', 'Email Sent Successfully');

                return $this->redirectToRoute('user_login');
            }
            $this->addFlash('danger', 'Email Not Found');
        }

        return $this->render('security/user_email.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset-password/{confirmationToken}", name="reset_password", methods={"GET", "POST"})
     */
    public function resetPassword(Request $request, User $user, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder): Response
    {
        if (!$user) {
            $this->addFlash('danger', 'security.errors.user_not_found');

            return $this->redirectToRoute('user_login');
        }
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $form->get('password')->getData()))
                ->setConfirmationToken(null)
            ;
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Password Updated.');

            return $this->redirectToRoute('user_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirm-email/{confirmationToken}", name="email_confirmation", methods={"GET"})
     */
    public function confirmEmail(User $user, EntityManagerInterface $em): Response
    {
        if (!$user) {
            $this->addFlash('danger', 'security.errors.user_not_found');

            return $this->redirectToRoute('user_register');
        }
        $user->setConfirmationToken(null)
            ->setIsEnabled(true)
        ;
        $em->persist($user);
        $em->flush();
        $this->addFlash('success', 'Email Confirmed.');

        return $this->redirectToRoute('user_login');
    }
}
