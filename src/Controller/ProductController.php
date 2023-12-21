<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{


    #[Route('/products', name: 'product_list') ]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $products = $entityManager->getRepository(Product::class)->findAll();
    
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'controller_name' => 'ProductController',
        ]);
    }


    #[Route('/product', name: 'create_product', methods: ['POST'])]
    public function createProduct(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $product = new Product();
        $product->setName($request->request->get('name'));
        $product->setDescription($request->request->get('description'));
        $product->setPrice($request->request->get('price'));
    
        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new JsonResponse(['success' => false, 'error' => (string) $errors], 400);
        }
        $entityManager->persist($product);
        $entityManager->flush();
    
        return new JsonResponse(['success' => true]);
    }


    

    #[Route('/product/{id}', name: 'product_show')]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return new Response('Check out this great product: '.$product->getName());
    }

#[Route('/product/delete/{id}', name: 'delete_product', methods: ['POST'])]
public function deleteProduct(EntityManagerInterface $entityManager, int $id): Response
{
    $product = $entityManager->getRepository(Product::class)->find($id);

    if (!$product) {
        return new JsonResponse(['success' => false, 'error' => 'No product found for id '.$id], 404);
    }

    $entityManager->remove($product);
    $entityManager->flush();

    return new JsonResponse(['success' => true]);
}

    
}
