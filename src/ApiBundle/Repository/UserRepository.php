<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;

/**
 * Class UserRepository
 * @package ApiBundle\Repository
 */
class UserRepository extends EntityRepository
{
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $metadata = new ClassMetadata('ApiBundle:User');
        parent::__construct($entityManager, $metadata);
    }

    /**
     * @param $username
     * @return mixed
     */
    public function findOneByUsername($username)
    {
        return $this->getEntityManager()
            ->createQuery("SELECT u FROM ApiBundle:User u WHERE u.username = :username")
            ->setParameter('username', $username)
            ->getOneOrNullResult();
    }
}