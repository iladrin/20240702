<?php

namespace App\Controller;

use App\Data\ContactData;
use App\Entity\User;
use App\Form\ContactType;
use App\Data\ContactHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MainController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function contact(Request $request, #[CurrentUser] ?User $user, ContactHandler $contactHandler): Response
    {
        $data = new ContactData();
        if ($user) {
            $data->senderEmail = $user->getEmail();
        }

        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // handle contact data
            $contactHandler->sendEmail($form->getData());

            return $this->redirectToRoute('app_contact_success');
        }

        return $this->render('main/contact.html.twig', ['contact_form' => $form]);
    }

    #[Route('/contact/success', name: 'app_contact_success')]
    public function contactSuccess(): Response
    {
        return $this->render('main/contact_success.html.twig');
    }
}