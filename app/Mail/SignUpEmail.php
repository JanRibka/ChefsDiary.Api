<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Mail;

use DateTime;
use JR\ChefsDiary\Config;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\BodyRendererInterface;
use JR\ChefsDiary\Entity\User\Contract\UserInfoInterface;
use JR\ChefsDiary\Services\Implementation\SignedUrlService;

class SignUpEmail
{
    public function __construct(
        private readonly Config $config,
        private readonly MailerInterface $mailer,
        private readonly BodyRendererInterface $renderer,
        private readonly SignedUrlService $signedUrlService,

    ) {
    }

    public function send(UserInfoInterface $userInfo): void
    {
        $email = $userInfo->getEmail();
        $expirationDate = new DateTime('+30 minutes');
        $activationLink = $this->signedUrlService->fromRoute(
            'verify',
            ['id' => $userInfo->getUser()->getId(), 'hash' => sha1($email)],
            $expirationDate
        );

        $message = (new TemplatedEmail())
            ->from($this->config->get('mailer.from'))
            ->to($email)
            ->subject('Welcome to Expennies')
            ->htmlTemplate('emails/signup.html.twig')
            ->context(
                [
                    'activationLink' => $activationLink,
                    'expirationDate' => $expirationDate,
                ]
            );

        $this->renderer->render($message);

        $this->mailer->send($message);
    }
}