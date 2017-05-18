<?php

namespace ContactsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContactsGroup
 *
 * @ORM\Table(name="contacts_group")
 * @ORM\Entity(repositoryClass="ContactsBundle\Repository\ContactsGroupRepository")
 */
class ContactsGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Person", inversedBy="groups")
     */
    private $persons;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ContactsGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function __construct() {
        $this->persons = new ArrayCollection();
    }


    /**
     * Add person
     *
     * @param \ContactsBundle\Entity\Person $person
     *
     * @return ContactsGroup
     */
    public function addPerson(\ContactsBundle\Entity\Person $person)
    {
        $this->persons[] = $person;

        return $this;
    }

    /**
     * Remove person
     *
     * @param \ContactsBundle\Entity\Person $person
     */
    public function removePerson(\ContactsBundle\Entity\Person $person)
    {
        $this->persons->removeElement($person);
    }

    /**
     * Get persons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersons()
    {
        return $this->persons;
    }
}
