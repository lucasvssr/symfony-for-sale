<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/{user}/advertisements', name: 'app_user_advertisements', requirements: ['user' => '\d+'])]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request, $user): Response
    {
        $user = $userRepository->findOneBy(['id' => $user]);
        $advertisements = $user->getAdvertisements()->toArray();

        usort($advertisements, fn ($a, $b) => $a->getCreatedAt() < $b->getCreatedAt());

        $pagination = $paginator->paginate($advertisements, $request->query->getInt('page', 1), 15);

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'advertisements' => $pagination,
            'currentUser' => $this->getUser(),
        ]);
    }
}
