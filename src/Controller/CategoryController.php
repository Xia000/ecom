<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;



class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $categories = $doctrine->getRepository(Category::class)->findAll();
        
        
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/category/create", name="create_category")
     */

    public function create(Environment $twig, Request $request, EntityManagerInterface $entityManager)
     {

        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('app_category'));
            
        }

        return new Response($twig->render('category/create.html.twig', [
            'category_form' => $form->createView(),
        ]));
     }

    /**
     * @Route("/category/edit/{id}", name="edit_category")
     */
    public function edit(Environment $twig, Request $request, EntityManagerInterface $entityManager, $id)
    {
        $category = $entityManager->getRepository(Category::class)->find($id);

        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();

            return new Response( 'Saved new Category with id: '.$category->getId() );
        }

        return new Response($twig->render('category/edit.html.twig', [
            'category_form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/category/delete/{id}", name="delete_category")
     */

    public function delete(Category $category,EntityManagerInterface $entityManager)
    {
       $product = $entityManager->getRepository(Product::class)->findBy(['category' => $category]);

         if(count($product) > 0){
              return new Response('This category is used in products');
            }

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirect($this->generateUrl('app_category'));
        
    }


}
