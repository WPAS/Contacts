<?php

namespace ContactsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use ContactsBundle\Entity\ContactsGroup;


/**
 * @Route("/group")
 */
class ContactsGroupController extends Controller
{
    /**
     * @Route("/new", name="new_group")
     */
    public function newAction(Request $request)
    {
        $group = new ContactsGroup();
        
        $form = $this->createFormBuilder($group)
                ->add("name", "text")
                ->add("save", "submit", array("label"=>"Dodaj grupę"))
                ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()){
            $group = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();            
            return $this->redirect($this->generateUrl('groups'));
        }
        
        return $this->render('ContactsBundle:ContactsGroup:create.html.twig', array(
            'form' => $form->createView()
        ));
   
    }
    
     /**
     * @Route("/all", name="groups")
     */
    public function all()
    {
        $groupsRepository = $this->getDoctrine()->getRepository('ContactsBundle:ContactsGroup');
        $groups = $groupsRepository->findAll();
        if (!$groups) {
            throw new NotFoundHttpException('Brak grup');
        }
        return $this->render('ContactsBundle:ContactsGroup:showAll.html.twig', ['groups' => $groups]);
    }

    /**
    * @Route("/{id}", name="group")
    */
    public function show($id)
    {
        $groupsRepository = $this->getDoctrine()->getRepository('ContactsBundle:ContactsGroup');
        $group = $groupsRepository->find($id);
        if (!$group) {
            throw new NotFoundHttpException('Nie ma takiej grupy');
        }
        return $this->render('ContactsBundle:ContactsGroup:show.html.twig', ['group' => $group]);
    }


}
