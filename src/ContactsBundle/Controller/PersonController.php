<?php

namespace ContactsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use ContactsBundle\Entity\Person;

class PersonController extends Controller
{
    /**
     * @Route("/new", name="new")
     */
    public function newAction(Request $request)
    {
        $person = new Person();
        
        $form = $this->createFormBuilder($person)
                ->add("firstName", "text")
                ->add("lastName", "text")
                ->add("description", "textarea")
                ->add("save", "submit", array("label"=>"Dodaj osobę"))
                ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()){
            $person = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();            
            return $this->redirect($this->generateUrl('show', [ 'id' => $person->getId() ] ));
        }
        
        return $this->render('ContactsBundle:Person:create.html.twig', array(
            'form' => $form->createView()
        ));
   
    }
    
    /**
     * @Route("/{id}/modify", name="modify")
    */
    public function modifyAction(Request $request, $id)
    {
    
        $personRepository = $this->getDoctrine()->getRepository("ContactsBundle:Person");
        $person = $personRepository->find($id);
        if (!$person) {
            throw new NotFoundHttpException('Nie znaleziono takiej osoby');
        }
        $form = $this->createFormBuilder($person)
                ->add("firstName", "text")
                ->add("lastName", "text")
                ->add("description", "textarea")
                ->add("save", "submit", array("label"=>"Zapisz nowe dane"))
                ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $person = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();            
            return new Response ("Dane osoby zostały zmienione");
        }
        
        return $this->render('ContactsBundle:Person:create.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/{id}/delete", name="delete")
    */
    public function deleteAction($id)
    {
        $personRepository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $personRepository->find($id);
        if (!$person) {
            throw new NotFoundHttpException('Nie znaleziono takiej osoby');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($person);
        $em->flush();
        return new Response('Pomyślnie usunięto osobę');
    }
    
    /**
    * @Route("/{id}", name="show")
    */
    public function show($id)
    {
        $personRepository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $personRepository->find($id);
        if (!$person) {
            throw new NotFoundHttpException('Niestety, nie mamy tej osoby w bazie');
        }
         		
        return $this->render('ContactsBundle:Person:show.html.twig', ['person' => $person]);      
    }

    /**
     * @Route("/")
     */
    public function all()
    {
        $personsRepository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $persons = $personsRepository->getAlphabetically();
        if (!$persons) {
            throw new NotFoundHttpException('Brak wpisów');
        }
        return $this->render('ContactsBundle:Person:showAll.html.twig', ['persons' => $persons]);

    }


    
    
}
