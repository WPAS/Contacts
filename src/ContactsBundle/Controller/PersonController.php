<?php

namespace ContactsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use ContactsBundle\Entity\Person;
use ContactsBundle\Entity\Address;
use ContactsBundle\Entity\Phone;
use ContactsBundle\Entity\Email;

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
            return $this->redirect($this->generateUrl('show', [ 'id' => $person->getId() ]));
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
        
        $address = new Address();
        
        $addressForm = $this->createFormBuilder($address)
                ->setAction($this->generateUrl('address', array('id' => $id)))
                ->add("city", "text")
                ->add("street", "text")
                ->add("house", "text")
                ->add("flat", "text")
                ->add("saveAddress", "submit", array("label"=>"Dodaj adres"))
                ->getForm();
        
        $phone = new Phone();
        
        $phoneForm = $this->createFormBuilder($phone)
                ->setAction($this->generateUrl('phone', array('id' => $id)))
                ->add("number", "number")
                ->add("type", "text")
                ->add("savePhone", "submit", array("label"=>"Dodaj numer telefonu"))
                ->getForm();
        
        $email = new Email();
        
        $emailForm = $this->createFormBuilder($email)
                ->setAction($this->generateUrl('email', array('id' => $id)))
                ->add("email", "email")
                ->add("type", "text")
                ->add("saveEmail", "submit", array("label"=>"Dodaj adres email"))
                ->getForm();

        return $this->render('ContactsBundle:Person:modify.html.twig', array(
            'form' => $form->createView(), 
            'addressForm' => $addressForm->createView(), 
            'phoneForm' => $phoneForm->createView(),
            'emailForm' => $emailForm->createView()
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
    
    
    /**
     * @Route("/{id}/addAddress", name="address")
     * @Method("POST")
     */
    public function addAddress(Request $request, $id)
    {        
        $address = new Address();
        $personRepository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $personRepository->find($id);

        $addressForm = $this->createFormBuilder($address)
                ->setAction($this->generateUrl('address', array('id' => $id)))
                ->add("city", "text")
                ->add("street", "text")
                ->add("house", "text")
                ->add("flat", "text")
                ->add("saveAddress", "submit", array("label"=>"Dodaj adres"))
                ->getForm();

        $addressForm->handleRequest($request);
        
        $address = $addressForm->getData();
        $address->setPerson($person);
        $em = $this->getDoctrine()->getManager();
        $em->persist($address);
        $em->flush();            
        return $this->redirect($this->generateUrl('show', [ 'id' => $id ]));
        
    }

    /**
     * @Route("/{id}/addPhone", name="phone")
     * @Method("POST")
     */
    public function addPhone(Request $request, $id)
    {        
        $phone = new Phone();
        $personRepository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $personRepository->find($id);

        $phoneForm = $this->createFormBuilder($phone)
                ->setAction($this->generateUrl('phone', array('id' => $id)))
                ->add("number", "number")
                ->add("type", "text")
                ->add("savePhone", "submit", array("label"=>"Dodaj numer telefonu"))
                ->getForm();

        $phoneForm->handleRequest($request);
        
        $phone = $phoneForm->getData();
        $phone->setPerson($person);
        $em = $this->getDoctrine()->getManager();
        $em->persist($phone);
        $em->flush();            
        return $this->redirect($this->generateUrl('show', [ 'id' => $id ]));
        
    }

    /**
     * @Route("/{id}/addEmail", name="email")
     * @Method("POST")
     */
    public function addEmail(Request $request, $id)
    {        
        $email = new Email();
        $personRepository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $personRepository->find($id);

        $emailForm = $this->createFormBuilder($email)
                ->setAction($this->generateUrl('email', array('id' => $id)))
                ->add("email", "email")
                ->add("type", "text")
                ->add("saveEmail", "submit", array("label"=>"Dodaj adres email"))
                ->getForm();

        $emailForm->handleRequest($request);
        
        $email = $emailForm->getData();
        $email->setPerson($person);
        $em = $this->getDoctrine()->getManager();
        $em->persist($email);
        $em->flush();            
        return $this->redirect($this->generateUrl('show', [ 'id' => $id ]));
        
    }
    
    
}
