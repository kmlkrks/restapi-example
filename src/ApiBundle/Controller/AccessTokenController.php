<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Constraints as Asserts;

class AccessTokenController extends Controller
{
    /**
     * @param Request $request
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @Route("/access-tokens")
     * @Method({"POST"})
     */
    public function postAccessToken(Request $request)
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
        $user = $userService->checkUsernameAndPassword($username, $password);

        if (!$user) {
            return ['success' => false, 'message' => 'Bad credentials'];
        }

        $accessTokenService = $this->get('apibundle.service.access_token_service');
        $accessToken = $accessTokenService->generateAccessToken();

        $accessTokenService->insertAccessToken($accessToken, $username);

        return [
            'accessToken' => $accessToken,
            'username' => $user->getUsername()
        ];
    }
} 