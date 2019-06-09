<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;

/**
 * Class UserRepository
 *
 * @package AppBundle\Repository
 */
class UserRepository extends EntityRepository
{
    /**
     * @param User $user
     *
     * @throws OptimisticLockException
     */
    public function add(User $user) {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    /**
     * @param User $user
     *
     * @throws OptimisticLockException
     */
    public function delete(User $user) {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }
}
