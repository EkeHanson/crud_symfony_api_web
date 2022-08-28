<?php

namespace App\Controller;
use App\Entity\Crud;
use App\Form\CrudType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
     * @Route("/main", name="main.")
     */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        $data = $this->getDoctrine()->getRepository(Crud::class)->findAll();
         
        return $this->render('home/index.html.twig', [
            'lists' => $data
        ]);
    }
    /**
     * @Route("/post", name="post")
     */
    public function post(Request $request)
    {
        $crud = new Crud();
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($crud );
            $em->flush();

            $this->addFlash('notice','Action Submiited Successfully!!!');

            return $this->redirectToRoute('main.main');
        }

        return $this->render('home/post.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/update/{id}", name="update")
     */

    public function update(Request $request, $id)
    {

        $crud = $this->getDoctrine()->getRepository(Crud::class)->find($id);
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($crud );
            $em->flush();

            return $this->redirectToRoute('main.main');
        }

        return $this->render('home/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/delete/{id}", name="delete")
     */

    public function delete(Request $request, $id)
    {

        $data = $this->getDoctrine()->getRepository(Crud::class)->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($data);
            $em->flush();

            $this->addFlash('notice','Action delete Submiited Successfully!!!');

            return $this->redirectToRoute('main.main');
       
    }
}
