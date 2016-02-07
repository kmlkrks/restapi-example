<?php

namespace ApiBundle\Service;

use ApiBundle\Entity\User;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

/**
 * Class UserService
 * @package ApiBundle\Service
 */
class UserService
{
    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactory
     */
    private $encoderFactory;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $userRepository;

    /**
     * @param EncoderFactory $encoderFactory
     * @param EntityManager $entityManager
     * @param EntityRepository $userRepository
     */
    public function __construct(
        EncoderFactory $encoderFactory,
        EntityManager $entityManager,
        EntityRepository $userRepository
    ) {
        $this->encoderFactory = $encoderFactory;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @param $username
     * @param $password
     * @return DBALException|\Exception
     */
    public function insertUser(
        $username,
        $password
    ) {
        $this->entityManager->beginTransaction();
        try {
            $user = new User();
            $encoder = $this->encoderFactory->getEncoder($user);
            $user->setSalt(md5(time()));
            $password = $encoder->encodePassword($password, $user->getSalt());
            $user->setUsername($username);
            $user->setEmail($username);
            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->entityManager->commit();
            return $user;
        } catch (DBALException $e) {
            $this->entityManager->rollback();
            return $e;
        }
    }

    /**
     * @param $username
     * @return mixed
     */
    public function getUserByUsername($username)
    {
        return $this->userRepository->findOneByUsername($username);
    }

    /**
     * @param $username
     * @param $password
     * @return bool|mixed
     */
    public function checkUsernameAndPassword($username, $password)
    {
        $user = $this->getUserByUsername($username);

        if (!$user) {
            return false;
        }
        $encoder = $this->encoderFactory->getEncoder($user);
        $password = $encoder->encodePassword($password, $user->getSalt());

        if ($password == $user->getPassword()) {
            return $user;
        }

        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserById($id)
    {
        return $this->userRepository->findOneById($id);
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteUserById($id)
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return false;
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return true;
    }

    /**
     * @param $id
     * @param $username
     * @return DBALException|\Exception|null|object
     */
    public function updateUser($id, $username)
    {
        $this->entityManager->beginTransaction();
        try {
            $user = $this->userRepository->find($id);
            $user->setUsername($username);
            $user->setEmail($username);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->entityManager->commit();
            return $user;
        } catch (DBALException $e) {
            $this->entityManager->rollback();
            return $e;
        }
    }
} 