<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Entity\Product; // Import the Product entity if needed
use App\Form\ProductType; // Import the ProductType form class if needed

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
/*
    #[Route('/product/{id<\d+>}')]
    public function show($id, ProductRepository $repository): Response
    {
        $product = $repository->find($id); // findOneBy(['id' => $id]);
        //dd($id); // This will dump the product ID and stop execution

        if ($product === null) {
            
            throw $this->createNotFoundException('Product not found');

        }

        return $this->render('product/show.html.twig', [
            'product' => $product, // this array hands arguments to the render method
        ]);
    }
*/
    #[Route('/product/{id<\d+>}', name: 'product_show')]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product, // this array hands arguments to the render method
        ]);
    } // This automatically handles 404 errors if the product is not found, thanks to the route parameter type hinting.

    #[Route('/product/new', name: 'product_new')]
    public function new(): Response
    {
        $form = $this->createForm(ProductType::class);

        return $this->render('product/new.html.twig', [
            'form' => $form, 
        ]);
    }

}