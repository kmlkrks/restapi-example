<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Constraints as Asserts;

/**
 * Class UserController
 * @package ApiBundle\Controller
 */
class UserController extends Controller
{
    /**
     * @param $id
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @Route("/users/{id}")
     * @Method({"GET"})
     */
    public function getUserAction($id)
    {
        $user = $this->get('apibundle.service.user_service')->getUserById($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        return $user;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @Route("/users/{id}")
     * @Method({"DELETE"})
     */
    public function deleteUserAction($id)
    {
        $userService = $this->get('apibundle.service.user_service');
        $user = $userService->getUserById($id);

        if (!$user) {
            throw $this->createNotFoundException();
        }

        $userService->deleteUserById($id);
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @Route("/users/{id}")
     * @Method({"PUT"})
     */
    public function putUserAction(Request $request)
    {
        $collectionConstraint = new Asserts\Collection(array(
            'username' => array(
                new Asserts\NotBlank(),
                new Asserts\Email(),
            )
        ));

        $errors = $this->get('validator')->validate($request->request->all(), $collectionConstraint);

        if (count($errors) !== 0) {
            throw new UnprocessableEntityHttpException();
        }

        $username = $request->get('username');
        $id = $request->get('id');

        $userService = $this->get('apibundle.service.user_service');
        $user = $userService->getUserById($id);

        if (!$user) {
            throw $this->createNotFoundException();
        }

        $updatedUser = $userService->updateUser($id, $username);

        if ($updatedUser instanceof UserInterface) {
            return array(
                'success' => true,
                'id' => $updatedUser->getId()
            );
        } else {
            return array(
                'success' => false,
            );
        }

    }

    /**
     * @param Request $request
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException
     * @Route("/users")
     * @Method({"POST"})
     */
    public function postUser(Request $request)
    {
        $collectionConstraint = new Asserts\Collection(array(
            'username' => array(
                new Asserts\NotBlank(),
                new Asserts\Email(),
            ),
            'password' => array(
                new Asserts\NotBlank(),
                new Asserts\Length(['min' => 6]),
            ),
        ));

        $errors = $this->get('validator')->validate($request->request->all(), $collectionConstraint);

        if (count($errors) !== 0) {
            throw new UnprocessableEntityHttpException();
        }

        $username = $request->get('username');
        $password = $request->get('password');

        $userService = $this->get('apibundle.service.user_service');
        $user = $userService->insertUser($username, $password);

        if ($user instanceof UserInterface) {
            return array(
                'success' => true,
                'id' => $user->getId()
            );
        } else {
            return array(
                'success' => false,
            );
        }
    }
} 