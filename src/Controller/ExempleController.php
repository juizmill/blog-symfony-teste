<?php

namespace App\Controller;

use App\Document\User;
use App\Document\BlogPost;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExempleController extends AbstractController
{
    public function __construct(protected DocumentManager $documentManager) {}


    #[Route('/user', name: 'app_user')]
    public function newUser(): Response
    {
        $this->createUser();

        dd('usuario criado');
    }


    #[Route('/posts', name: 'app_posts')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->documentManager->getRepository(User::class)->findOneBy([]);

        $blogPost = $this->createPost();
//        $blogPost->setAuthor($user); // Add usuario/autor direto no post

        $this->documentManager->flush();

        /** @var BlogPost $post */
        $posts = $this->documentManager->getRepository(BlogPost::class)->findAll();
        $post = end($posts);

        $user->addPost($post); // Add post direto no usuario/autor

        //$user->removePost($post); //Removendo um post pelo usuario/autor

//      $post->removeAuthor(); //removendo autor direto pelo post

        $this->documentManager->flush();

        dd($post->getAuthor(), $user->posts());


        return $this->render('exemple/index.html.twig', [
            'controller_name' => 'ExempleController',
        ]);
    }

    public function createPost(): BlogPost
    {
        $blogPost = new BlogPost();
        $blogPost->title = 'Exemplo ' . time();
        $blogPost->body = 'Body Exemplo ' . time();

        $this->documentManager->persist($blogPost);
        $this->documentManager->flush();

        /** @var BlogPost $post */
        $posts = $this->documentManager->getRepository(BlogPost::class)->findAll();
        $post = end($posts);

        return $post;
    }


    protected function createUser()
    {
        $user = new User();
        $user->name = 'Jesus Vieira';
        $user->email = 'tete@teste.com.br';

        $this->documentManager->persist($user);
        $this->documentManager->flush();
    }


}