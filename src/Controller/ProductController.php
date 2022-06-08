<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use App\Form\ProductFormType;
use Doctrine\Persistence\ManagerRegistry;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="product_index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $products = $doctrine->getRepository(Product::class)->findAll();
        // dd($products);
        
        return $this->render('product/index.html.twig', [
            'products' => $products,
            
        ]);
    }

    /**
     * @Route("/product/create", name="create_product")
     */

    public function create(Environment $twig, Request $request, EntityManagerInterface $entityManager)
    {
       

        $product = new Product();

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($product);
            $entityManager->flush();

            return new Response( 'Saved new product with id: '.$product->getId() );
        }

        return new Response($twig->render('product/create.html.twig', [
            'product_form' => $form->createView(),
        ]));

    }


    /**
     * @Route("/product/edit/{id}", name="edit_product")
     */
    public function edit(Environment $twig, Request $request, EntityManagerInterface $entityManager, $id)
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($product);
            $entityManager->flush();

            return new Response( 'Saved new product with id: '.$product->getId() );
        }

        return new Response($twig->render('product/edit.html.twig', [
            'product_form' => $form->createView(),
        ]));

    }

    /**
     * @Route("/product/delete/{id}", name="delete_product")
     */

     public function delete(Product $product,EntityManagerInterface $entityManager)
     {
         $entityManager->remove($product);
         $entityManager->flush();

         return $this->redirect($this->generateUrl('product_index'));
        
         return new Response( 'Deleted product with id: '.$product->getId() );
     }
    
}
