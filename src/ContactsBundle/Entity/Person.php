<?php

namespace ContactsBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="ContactsBundle\Repository\PersonRepository")
 */
class Person
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
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @ORM\OneToMany(targetEntity="Address", mappedBy="person", cascade={"persist", "remove"})
     */
    private $addresses;
    
    /**
     * @ORM\OneToMany(targetEntity="Phone", mappedBy="person", cascade={"persist", "remove"})
     */
    private $phones;

    /**
     * @ORM\OneToMany(targetEntity="Email", mappedBy="person", cascade={"persist", "remove"})
     */
    private $emails;

    /**
     * @ORM\ManyToMany(targetEntity="ContactsGroup", mappedBy="persons")
     * @ORM\JoinTable(name="contacts_group_person")
     */
    private $groups;


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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Person
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Person
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Person
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct() {
        $this->comments = new ArrayCollection();
        $this->phones = new ArrayCollection();
        $this->emails = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    /**
     * Add address
     *
     * @param \ContactsBundle\Entity\Address $address
     *
     * @return Person
     */
    public function addAddress(\ContactsBundle\Entity\Address $address)
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Remove address
     *
     * @param \ContactsBundle\Entity\Address $address
     */
    public function removeAddress(\ContactsBundle\Entity\Address $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Add phone
     *
     * @param \ContactsBundle\Entity\Phone $phone
     *
     * @return Person
     */
    public function addPhone(\ContactsBundle\Entity\Phone $phone)
    {
        $this->phones[] = $phone;

        return $this;
    }

    /**
     * Remove phone
     *
     * @param \ContactsBundle\Entity\Phone $phone
     */
    public function removePhone(\ContactsBundle\Entity\Phone $phone)
    {
        $this->phones->removeElement($phone);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Add email
     *
     * @param \ContactsBundle\Entity\Email $email
     *
     * @return Person
     */
    public function addEmail(\ContactsBundle\Entity\Email $email)
    {
        $this->emails[] = $email;

        return $this;
    }

    /**
     * Remove email
     *
     * @param \ContactsBundle\Entity\Email $email
     */
    public function removeEmail(\ContactsBundle\Entity\Email $email)
    {
        $this->emails->removeElement($email);
    }

    /**
     * Get emails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * Add group
     *
     * @param \ContactsBundle\Entity\ContactsGroup $group
     *
     * @return Person
     */
    public function addGroup(\ContactsBundle\Entity\ContactsGroup $group)
    {
        $group->addPerson($this);
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param \ContactsBundle\Entity\ContactsGroup $group
     */
    public function removeGroup(\ContactsBundle\Entity\ContactsGroup $group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
