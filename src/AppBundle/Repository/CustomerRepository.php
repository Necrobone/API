<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Customer;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;

/**
 * Class CustomerRepository
 *
 * @package AppBundle\Repository
 */
class CustomerRepository extends EntityRepository
{
    /**
     * @param Customer $customer
     *
     * @throws OptimisticLockException
     */
    public function add(Customer $customer) {
        $em = $this->getEntityManager();
        $em->persist($customer);
        $em->flush();
    }

    /**
     * @param Customer $customer
     *
     * @throws OptimisticLockException
     */
    public function delete(Customer $customer) {
        $em = $this->getEntityManager();
        $em->remove($customer);
        $em->flush();
    }
}
