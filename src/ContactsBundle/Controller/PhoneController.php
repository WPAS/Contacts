<?php

namespace ContactsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use ContactsBundle\Entity\Phone;

class PhoneController extends Controller
{
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
        
        $this->addFlash('notice', 'Pomyślnie zmieniono dane osoby');

        return $this->redirect($this->generateUrl('show', [ 'id' => $id ]));
        
    }

    /**
     * @Route("/{id}/deletePhone", name="deletePhone")
    */
    public function deletePhoneAction($id)
    {
        $phoneRepository = $this->getDoctrine()->getRepository('ContactsBundle:Phone');
        $phone = $phoneRepository->find($id);
        if (!$phone) {
            throw new NotFoundHttpException('Nie znaleziono takiego telefonu');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($phone);
        $em->flush();
        
        $this->addFlash('notice', 'Pomyślnie usunięto numer telefonu');
        
        return $this->redirectToRoute('index');        
    }

}
