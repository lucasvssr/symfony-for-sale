<?php

namespace App\Controller;

use App\Repository\AdvertisementRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAllByName(),
        ]);
    }

    #[Route('/category/{category}', name: 'app_category_show', requirements: ['category' => '\d+'])]
    public function show(CategoryRepository $categoryRepository, int $category, AdvertisementRepository $advertisementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $advertisementRepository->findByCategory($category);
        $pagination = $paginator->paginate(
            $categories,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('category/show.html.twig', [
            'category_show' => $categoryRepository->find($category),
            'advertisements' => $pagination,
        ]);
    }
}
