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
    public function allAction()
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
    public function showAction($id)
    {
        $groupsRepository = $this->getDoctrine()->getRepository('ContactsBundle:ContactsGroup');
        $group = $groupsRepository->find($id);
        if (!$group) {
            throw new NotFoundHttpException('Nie ma takiej grupy');
        }
        return $this->render('ContactsBundle:ContactsGroup:show.html.twig', ['group' => $group]);
    }

    /**
    * @Route("/{id}/change", name="changeName")
    */
    public function changeAction(Request $request, $id)
    {
        $groupsRepository = $this->getDoctrine()->getRepository('ContactsBundle:ContactsGroup');
        $group = $groupsRepository->find($id);
        if (!$group) {
            throw new NotFoundHttpException('Nie ma takiej grupy');
        }
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
     * @Route("/{id}/delete", name="deleteGroup")
    */
    public function deleteAction($id)
    {
        $groupRepository = $this->getDoctrine()->getRepository('ContactsBundle:ContactsGroup');
        $group = $groupRepository->find($id);
        if (!$group) {
            throw new NotFoundHttpException('Nie znaleziono takiej grupy');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($group);
        $em->flush();

        $this->addFlash('notice', 'Pomyślnie usunięto grupę');

        return $this->redirectToRoute('groups');        
    }

    /**
     * @Route("/{id}/remove/{personId}", name="removePerson")
    */
    public function removeAction($id, $personId)
    {
        $groupRepository = $this->getDoctrine()->getRepository('ContactsBundle:ContactsGroup');
        $group = $groupRepository->find($id);
        if (!$group) {
            throw new NotFoundHttpException('Nie znaleziono takiej grupy');
        }
        $personRepository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $personRepository->find($personId);
        if (!$person) {
            throw new NotFoundHttpException('Nie znaleziono takiej osoby');
        }
        $group->removePerson($person);
        $person->removeGroup($group);
                
        $em = $this->getDoctrine()->getManager();
        $em->flush();
                
        $this->addFlash('notice', 'Pomyślnie usunięto osobę z grupy');

        return $this->redirectToRoute('groups');        
    }
    

}
