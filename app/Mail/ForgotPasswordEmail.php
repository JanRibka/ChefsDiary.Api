<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Mail;

use JR\ChefsDiary\Config;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\BodyRendererInterface;
use App\Entity\User\Contract\UserPasswordResetInterface;
use JR\ChefsDiary\Services\Implementation\SignedUrlService;

class ForgotPasswordEmail
{
    public function __construct(
        private readonly Config $config,
        private readonly MailerInterface $mailer,
        private readonly BodyRendererInterface $renderer,
        private readonly SignedUrlService $signedUrlService
    ) {
    }

    public function send(UserPasswordResetInterface $userPasswordReset): void
    {
        $email = $userPasswordReset->getEmail();
        $resetLink = $this->signedUrlService->fromRoute(
            'password-reset',
            ['token' => $userPasswordReset->getToken()],
            $userPasswordReset->getExpireDate()
        );
        $message = (new TemplatedEmail())
            ->from($this->config->get('mailer.from'))
            ->to($email)
            ->subject('Your Expennies Password Reset Instructions')
            ->htmlTemplate('emails/password_reset.html.twig')
            ->context(
                [
                    'resetLink' => $resetLink,
                ]
            );

        $this->renderer->render($message);

        $this->mailer->send($message);
    }
}
