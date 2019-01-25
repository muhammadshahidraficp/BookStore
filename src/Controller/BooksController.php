<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Book;

class BooksController extends Controller{
	/**
      * @Route("/books/author",name="books")
      */
    public function authorAction()
    {
       return $this->render('author.html.twig');

    }

//-----------------Display Action---------------------------------

    /**
      * @Route("/books/author",name="books")
      */
    public function displayAction()
    {  
       $book=$this->getDoctrine()->getRepository('App:Book')->findAll();	
       return $this->render('display.html.twig',array('data'=>$book));

    }


//-----------------Add Action--------------------------------------

    /**
      * @Route("/new",name="add")
      */
    public function addAction(Request $request)
    {  
       $newbook=new Book();
       $form=$this->createFormBuilder($newbook)
       			->add('name',TextType::class)
       			->add('author',TextType::class)
       			->add('price',TextType::class)
            ->add('shelf',TextType::class)
       			->add('save',SubmitType::class,array('label'=>'Submit'))
       			->getForm();	

            $form->handleRequest($request);
            if($form->isSubmitted()&&$form->isValid()){
              $book=$form->getData();
              $doct=$this->getDoctrine()->getManager();
              //tell Doctrine to save product
              $doct->persist($book);
              //execute query
              $doct->flush();
              return $this->redirectToRoute('books');
            }
            else{
                return $this->render('newbook.html.twig',array('form'=>$form->createView(),));       
            }
       

    }



    //-----------------Update Action---------------------------------

        /**
      * @Route("/update/{id}",name="update")
      */
    public function updateAction($id,Request $request)
    {  
       $doct=$this->getDoctrine()->getManager();
       $book=$doct->getRepository('App:Book')->find($id);
       if(!$book){
        throw $this->createNotFoundException('No book for id'.$id);
       }
       $form=$this->createFormBuilder($book)
       ->add('name',TextType::class)
            ->add('author',TextType::class)
            ->add('price',TextType::class)
            ->add('shelf',TextType::class)
            ->add('save',SubmitType::class,array('label'=>'Submit'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          $book=$form->getData();
          $doct=$this->getDoctrine()->getManager();

          //tell Doctrine save book
          $doct->persist($book);
          //execute queries
          $doct->flush();
          return $this->redirectToRoute('books');
        }
        else{
          return $this->render('newbook.html.twig',array('form'=>$form->createView(),));
        }    
}



    //-----------------Delete Action---------------------------------


        /**
      * @Route("/delete/{id}",name="delete")
      */
    public function deleteAction($id)
    {  
       $doct=$this->getDoctrine()->getManager();
       $book=$doct->getRepository('App:Book')->find($id);
       if(!$book){
        throw $this->createNotFoundException('No book for id'.$id);
       }
       $doct->remove($book);
       $doct->flush();
       return $this->redirectToRoute('books');    
}





}
