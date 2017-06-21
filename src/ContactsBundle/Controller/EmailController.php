<?php

namespace ContactsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use ContactsBundle\Entity\Phone;

class EmailController extends Controller
{
    /**
     * @Route("/{id}/addEmail", name="email")
     * @Method("POST")
     */
    public function addEmailAction(Request $request, $id)
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

        $this->addFlash('notice', 'Pomyślnie zmieniono dane osoby');

        return $this->redirect($this->generateUrl('show', [ 'id' => $id ]));
        
    }
        
    /**
     * @Route("/{id}/deleteEmail", name="deleteEmail")
    */
    public function deleteEmailAction($id)
    {
        $emailRepository = $this->getDoctrine()->getRepository('ContactsBundle:Email');
        $email = $emailRepository->find($id);
        if (!$email) {
            throw new NotFoundHttpException('Nie znaleziono takiego adresu email');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($email);
        $em->flush();
        
        $this->addFlash('notice', 'Pomyślnie usunięto adres email');
        
        return $this->redirectToRoute('index');        
    }
}
