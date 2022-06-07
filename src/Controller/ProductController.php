<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use App\Form\ProductFormType;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/product/create", name="create_product")
     */

    public function show(Environment $twig, Request $request, EntityManagerInterface $entityManager)
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

        // $product->setName('Product 3');

        // $em = $doctrine->getManager();
        // $em->persist($product);
        // $em->flush();

       
        // return new Response(
        //     'Saved new product with id: '.$product->getId()
        // );

    }
}
