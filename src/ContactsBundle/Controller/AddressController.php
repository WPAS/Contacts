<?php

namespace ContactsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use ContactsBundle\Entity\Address;

class AddressController extends Controller
{

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
        $this->addFlash('notice', 'Pomyślnie zmieniono dane osoby');

        return $this->redirect($this->generateUrl('show', [ 'id' => $id ]));
        
    }
    
    /**
     * @Route("/{id}/deleteAddress", name="deleteAddress")
    */
    public function deleteAddressAction($id)
    {
        $addressRepository = $this->getDoctrine()->getRepository('ContactsBundle:Address');
        $address = $addressRepository->find($id);
        if (!$address) {
            throw new NotFoundHttpException('Nie znaleziono takiego adresu');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($address);
        $em->flush();

        $this->addFlash('notice', 'Pomyślnie usunięto adres');
        
        return $this->redirectToRoute('index');        
    }

}
