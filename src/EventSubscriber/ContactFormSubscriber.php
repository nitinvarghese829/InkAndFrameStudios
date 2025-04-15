<?php

namespace App\EventSubscriber;

use App\Entity\ContactUs;
use App\Form\ContactUsFormType;
use App\Service\ApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class ContactFormSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private RequestStack $requestStack,
        private Environment $twig,
        private EntityManagerInterface $entityManager,
        private RouterInterface $router,
        private MailerInterface $mailer
    ) {}

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        $contact = new ContactUs();
        $form = $this->formFactory->create(ContactUsFormType::class, $contact);
        $form->handleRequest($request);

        // Handle submission
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            // Add a flash message if you want
            $request->getSession()->getFlashBag()->add('success', 'Thank you for contacting us!');

            $transport = Transport::fromDsn('smtp://inkandframestudios@gmail.com:cfyebgtpbsvlpyxc@smtp.gmail.com:587');
            /// Create a Mailer object
            $mailer = new Mailer($transport);
            $email = (new Email());


            $email = (new Email())
                ->from('inkandframestudios@gmail.com')
                ->to('nitinvarghese829@gmail.com') // or dynamically from settings
                ->subject('New Contact Form Submission')
                ->html('<p>Hi,</p>
            <p>You have an inquiry from website</p>

            <h2>New Contact Request</h2>
            <p>
            	<strong>Name:</strong>
            	' . $form->get('name')->getData() . '</p>
            <p>
            	<strong>Email:</strong>
            	' . $form->get('email')->getData() . '</p>
            <p>
            	<strong>Phone:</strong>
            	' . $form->get('phoneNo')->getData() . '</p>
            <p>
            	<strong>Message:</strong><br>' . $form->get('message')->getData() . '</p>
            ');

            $mailer->send($email);

            // Redirect to avoid form re-submission on page refresh
            $route = $request->attributes->get('_route');
            $params = $request->attributes->get('_route_params', []);
            $response = new RedirectResponse($this->router->generate($route, $params));
            $event->setController(fn() => $response);

            $data = [
                'name' => $form->get('name')->getData(),
                'email' => $form->get('email')->getData(),
                'phoneNo' => $form->get('phoneNo')->getData(),
                'message' => $form->get('message')->getData()
            ];
            // ApiService::post(
            //     'https://hook.eu2.make.com/fix8alkjj4el8yg0secj9wh3s2xso90x',
            //     json_encode($data),
            //     [
            //         'Content-Type' => 'application/json'
            //     ]
            // );
            return;
        }

        // Share the form view with Twig
        $this->twig->addGlobal('contactForm', $form->createView());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
