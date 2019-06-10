<?php

namespace AppBundle\Repository;

use AppBundle\Entity\CustomerOrder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;

/**
 * Class CustomerOrderRepository
 *
 * @package AppBundle\Repository
 */
class CustomerOrderRepository extends EntityRepository
{
    /**
     * @param CustomerOrder $customerOrder
     *
     * @throws OptimisticLockException
     */
    public function add(CustomerOrder $customerOrder) {
        $em = $this->getEntityManager();
        $em->persist($customerOrder);
        $em->flush();
    }

    /**
     * @param CustomerOrder $customerOrder
     *
     * @throws OptimisticLockException
     */
    public function delete(CustomerOrder $customerOrder) {
        $em = $this->getEntityManager();
        $em->remove($customerOrder);
        $em->flush();
    }
}
