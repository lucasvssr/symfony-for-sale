<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Form\AdvertisementType;
use App\Repository\AdvertisementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdvertisementController extends AbstractController
{
    #[Route('/advertisement', name: 'app_advertisement')]
    public function index(AdvertisementRepository $advertisementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if ($request->get('search')) {
            $advertisements = $advertisementRepository->search($request->get('search'));
            $pagination = $paginator->paginate(
                $advertisements,
                $request->query->getInt('page', 1),
                15
            );

            return $this->render('advertisement/index.html.twig', [
                'advertisements' => $pagination, 'search' => $request->get('search'),
                'currentUser' => $this->getUser(),
            ]);
        }
        $advertisements = $advertisementRepository->queryAllByDate();
        $pagination = $paginator->paginate(
            $advertisements,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('advertisement/index.html.twig', [
            'advertisements' => $pagination,
            'currentUser' => $this->getUser(),
        ]);
    }

    #[Route('/advertisement/{advertisement}', name: 'app_advertisement_show', requirements: ['advertisement' => '\d+'])]
    public function show(
        #[MapEntity(expr: 'repository.getAdvertisementWithCategory(advertisement)')]
        Advertisement $advertisement): Response
    {
        $author = $advertisement->getAuthor();
        $firstname = $author->getFirstname();

        return $this->render('advertisement/show.html.twig', [
            'advertisement_show' => $advertisement,
            'owner' => $author,
        ]);
    }

    #[Route('/advertisement/create', name: 'app_advertisement_new', requirements: ['advertisement' => '\d+']), isGranted('ROLE_USER')]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $advertisement = new Advertisement();
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $advertisement = $form->getData();
            $entityManager->persist($advertisement);
            $entityManager->flush();

            return $this->redirectToRoute('app_advertisement');
        }

        return $this->render('advertisement/_form.html.twig', [
            'form' => $form,
            'advertisement' => $advertisement,
        ]);
    }

    #[Route('/advertisement/{advertisement}/edit', name: 'app_advertisement_edit', requirements: ['advertisement' => '\d+'])]
    #[IsGranted('POST_EDIT', 'advertisement')]
    public function edit(
        Request $request,
        ManagerRegistry $doctrine,
        #[MapEntity(expr: 'repository.getAdvertisementWithCategory(advertisement)')]
        Advertisement $advertisement): Response
    {
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $advertisement = $form->getData();
            $entityManager->persist($advertisement);
            $entityManager->flush();

            return $this->redirectToRoute('app_advertisement');
        }

        return $this->render('advertisement/_form.html.twig', [
            'form' => $form,
            'advertisement' => $advertisement,
        ]);
    }

    #[Route('/advertisement/{advertisement}/delete', name: 'app_advertisement_delete', requirements: ['advertisement' => '\d+'])]
    #[IsGranted('POST_DELETE', 'advertisement')]
    public function delete(
        Request $request,
        ManagerRegistry $doctrine,
        #[MapEntity(expr: 'repository.getAdvertisementWithCategory(advertisement)')]
        Advertisement $advertisement): Response
    {
        $submittedToken = $request->request->get('token');
        if (!$this->isCsrfTokenValid('delete-advertisement', $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($advertisement);
        $entityManager->flush();

        return $this->redirectToRoute('app_advertisement');
    }

    #[Route('/advertisement/liked', name: 'app_advertisement_liked')]
    public function like(
        PaginatorInterface $paginator,
        Request $request,
        AdvertisementRepository $advertisementRepository): Response
    {
        $user = $this->getUser(); // Supposons que vous utilisez Symfony Security pour gérer l'authentification

        $likedAdvertisements = $advertisementRepository->queryLikedByUser($user->getId());

        $pagination = $paginator->paginate(
            $likedAdvertisements,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('advertisement/likes.html.twig', [
            'advertisements' => $pagination,
            'currentUser' => $this->getUser(),
        ]);
    }
}
