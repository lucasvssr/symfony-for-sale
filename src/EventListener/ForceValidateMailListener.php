<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForceValidateMailListener
{
    private UrlGeneratorInterface $urlGenerator;
    private Security $security;

    public function __construct(UrlGeneratorInterface $urlGenerator, Security $security)
    {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$event->isMainRequest()) {
            return;
        }

        $user = $this->security->getUser();
        if ($user && $user instanceof User && !$user->isVerified()) {
            $allowedRoutes = ['app_verify_email', 'app_verify_email_resend', 'app_logout'];

            if (!in_array($request->get('_route'), $allowedRoutes)) {
                $url = $this->urlGenerator->generate('app_verify_email_resend');
                $response = new RedirectResponse($url);
                $event->setResponse($response);
            }
        }
    }
}
