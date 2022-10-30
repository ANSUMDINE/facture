<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product', name: 'admin_product')]
class ProductController extends AbstractController
{ 
    private $productService;

    public function __construct(
        ProductService $productService) 
    {
        $this->productService = $productService;
    }

    #[Route('', name: '')]
    public function index(): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $this->productService->getProduct() 
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request): Response
    {
        $product = new Product();
        $form = $this-> createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form-> isValid())
        {
            $this->productService->create($product);
            return $this->redirectToRoute('admin_product');
        }

        return $this->render('admin/product/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/update/{id}', name: '_update')]
    public function update(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $this->productService->update($product);
            return $this->redirectToRoute('admin_product');
        }

        return $this->render('admin/product/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: '_delete')]
    public function delete(Product $product): Response
    {
        $this->productService->delete($product);
        return $this->redirectToRoute('admin_product');
    }
}
