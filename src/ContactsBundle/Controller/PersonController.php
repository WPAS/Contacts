<?php

namespace ContactsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use ContactsBundle\Form\PersonType;
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
        
        $form = $this->createForm(PersonType::class, $person);
        
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

        $form = $this->createForm(PersonType::class, $person);
      
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $person = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
    
            $this->addFlash('notice', 'Pomyślnie zmieniono dane osoby');
        
            return $this->redirectToRoute('show', array('id' => $id));                    
        }
        
        $address = new Address();
        
        $addressForm = $this->createFormBuilder($address)
                ->setAction($this->generateUrl('address', array('id' => $id)))
                ->add("city", "text")
                ->add("street", "text")
                ->add("house", "text")
                ->add("flat", "text", array("required" => false))
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
        
        $this->addFlash('notice', 'Pomyślnie usunięto osobę');
        
        return $this->redirectToRoute('index');        
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
     * @Route("/", name="index")
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
