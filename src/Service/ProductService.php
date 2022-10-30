<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductService
{
    private $em;
    private $productRepository;

    public function __construct(
        EntityManagerInterface $em,
        ProductRepository $productRepository)
    {
        $this->em = $em;
        $this->productRepository = $productRepository;
    }

    public function getProduct(): array
    {
        return $this->productRepository->findAll();
    }

    public function create(Product $product): void
    {
        $this->em->persist($product);
        $this->em->flush();
    }

    public function update(Product $product) : void
    {
        $this->em->persist($product);
        $this->em->flush();
    }

    public function delete(Product $product): void
    {
        $this->em->remove($product);
        $this->em->flush();
    }
}