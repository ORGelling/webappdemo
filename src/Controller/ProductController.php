<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index')]
    public function index(ProductRepository $repository): Response
    {
        // $products = $repository->findAll(); // Retrieve all products using the repository

        // dump($products); // Debugs *and* executes the script
        // dd($products); // This will dump the products and stop execution

        return $this->render('product/index.html.twig', [
            'products' => $repository->findAll(),
        ]);
    }
}