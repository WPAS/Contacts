<?php

namespace ContactsBundle\Repository;

/**
 * PersonRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAlphabetically()
    {
   	$persons = $this->getEntityManager()->createQuery(
		'SELECT p FROM ContactsBundle:Person p ORDER BY p.lastName DESC')
		->getResult();
	return $persons;
    }
}
