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
            return [
                'id' => $user->getId()
            ];
        } else {
            return [
                'success' => false
            ];
        }

    }
} 