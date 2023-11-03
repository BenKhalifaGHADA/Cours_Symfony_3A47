<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    public $authors = array(


        array(
            'id' => 1, 'picture' => 'images/victor-hugo.jpg',
            'username' => ' Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100
        ),
        array(
            'id' => 2, 'picture' => 'images/william-shakespeare.jpg',
            'username' => ' William Shakespeare', 'email' => ' william.shakespeare@gmail.com', 'nb_books' => 200
        ),
        array(
            'id' => 3, 'picture' => 'images/Taha_Hussein.jpg',
            'username' => ' Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300
        ),
    );

    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/author/{n}', name: 'app_show')]
    public function showAuthor($n){
      return $this->render('author/show.html.twig',['name'=>$n]);
    }

    #[Route('/list',name: 'list')]
    public function list(){
        $authors = array(
            array('id' => 1, 'picture' => 'images/victor-hugo.jpg','username' => 'Victor Hugo', 'email' =>
                'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => 'images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
                ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => 'images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
                'taha.hussein@gmail.com', 'nb_books' => 300),
        );
    return $this->render('author/list.html.twig',['authors'=>$authors]);
    }
    #[Route('/show/{id}',name: 'show')]
    public function auhtorDetails ($id)
    {
        $author = null;
        // Parcourez le tableau pour trouver l'auteur correspondant à l'ID
        foreach ($this->authors as $authorData) {
            if ($authorData['id'] == $id) {
                $author = $authorData;
            };
        };
        return $this->render('author/showAuthor.html.twig', [
            'author' => $author,
            'id' => $id
        ]);
    }

    #[Route('/listAuthor', name: 'list_author')]
    public function listAuthor(AuthorRepository $authorepository): Response
    {
        $list=$authorepository->findAll();
        return $this->render('author/listAuthor.html.twig', [
            'authors' => $list,
        ]);
    }


    #[Route('/deleteauthor/{id}', name: 'author_delete')]
    public function deleteAuthor(Request $request, $id, ManagerRegistry $manager, AuthorRepository $authorepository): Response
    {
        //- Chercher un author selon son ID (Repository)  findBy($id)  /find($id)
        //- Suppression (EM) ( remove() & flush())

        //chercher l'auteur selon son id
        $author = $authorepository->find($id);

        $em = $manager->getManager();
        $em->remove($author);
        $em->flush();

        return $this->redirectToRoute('list_author');
    }

    #[Route('showA/{id}', name: 'showA')]
    public function showA($id,AuthorRepository $repo ){
       //- Chercher l’auteur selon son ID (Repositroy)  findby($id)/find($id)
        $author=$repo->find($id);
        return $this->render('author/showA.html.twig',['author'=>$author]);
    }

    #[Route('/addS', name: 'AddS')]
    public function AddStatic(ManagerRegistry $manager){
        // Ajout (EM)   (persist() &flush())
        $author=new Author();
        $author->setUsername('testStactic');
        $author->setEmail('test@gmail.com');
        $em=$manager->getManager();
        $em->persist($author);
        $em->flush();
        return new Response("Author added succesfully");

    }

    #[Route('/add', name: 'add')]
    public function add(Request $request,ManagerRegistry $manager){
        $author=new Author();
        $form=$this->createForm(AuthorType::class,$author);
        $form->add('add',SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em=$manager->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('list_author');
        }

       return $this->render('author/add.html.twig',['form'=>$form->createView()]);

    }

    #[Route('/update/{id}', name: 'update')]
    public function update($id,AuthorRepository $repo,ManagerRegistry $manager, Request $req){
        $authorOb=$repo->find($id);
        $form=$this->createForm(AuthorType::class,$authorOb);
        $form->add('Update',SubmitType::class);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em=$manager->getManager();
            $em->persist($authorOb);
            $em->flush();
            return $this->redirectToRoute('list_author');
        }

        return $this->render('author/update.html.twig',['form'=>$form->createView()]);
    }
    //Query Builder: Question 1
    #[Route('/author/list/OrderByEmail', name: 'app_author_list_ordered', methods: ['GET'])]
    public function listAuthorByEmail(AuthorRepository $authorRepository): Response
    {
        return $this->render('author/orderedList.html.twig', [
            'authors' => $authorRepository->showAllAuthorsOrderByEmail(),
        ]);
    }
}
