<?php

namespace App\Data;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class ContactHandler
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        LoggerInterface $logger,
        #[Autowire(param: 'contact_email')] private string $contactEmail,
        private Security $security
    )
    {
    }

    public function sendEmail(ContactData $data): void
    {
        if (!$this->security->isGranted('CONTACT')) {
            throw new \Exception('Unable to send email');
        }

        $message = (new TemplatedEmail())
            ->from($data->senderEmail)
            ->to($this->contactEmail)
            ->subject('Contact')
            ->htmlTemplate('emails/contact.html.twig')
            ->context([
                'content' => $data->message
            ])
        ;

        $this->mailer->send($message);
    }
}