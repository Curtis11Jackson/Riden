<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class RideController extends AbstractController
{
    /**
     * @Route("/ride", name="ride")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findAll();

        return $this->render('ride/index.html.twig', [
            'controller_name' => 'RideController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('ride/home.html.twig');
    }

   /**
     * @Route("/ride/new", name="ride_create")
     * @Route("/ride/{id}/edit", name="ride_edit")
     */
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager)
    {

        if(!$article) {
            $article = new Article();
        }
        

        // $form = $this->createFormBuilder($article)
        //             ->add('title')
        //             ->add('content')
        //             ->add('image')

        //             ->getForm();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            if($article->getId()){
            $article->setCreatedAt(new \DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('ride_show', ['id' => $article->getId()]);
        }

        return $this->render('ride/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    /**
     * @Route("/ride/{id}", name="ride_show")
     */
    public function show(Article $article, Request $request,  EntityManagerInterface $manager)
    {   

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
           
            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article);

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('ride_show', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('ride/show.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }

}

