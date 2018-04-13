<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;

/**
 * Class UserController
 * @package AppBundle\Controller
 */
class UserController extends FOSRestController
{
    /**
     * @SWG\Parameter(
     *      type="string",
     *      name="Authorization",
     *      in="header",
     *      required=true,
     *      description="Authorization header",
     *      default="Bearer _your_token_here_",
     *  )
     * @SWG\Response(
     *      response=200,
     *      description="User created successfull return user record",
     *  )
     * @SWG\Response(
     *      response=401,
     *      description="Invalid token",
     *      examples={
     *          "Token not found": "{code: 401, message: 'Token not found'}",
     *          "Expired token": "{code: 401, message: 'Expired Token'}",
     *      },
     *  )
     * @SWG\Parameter(
     *      type="string",
     *      name="username",
     *      in="path",
     *      required=true,
     *      description="User name",
     *      default="User name",
     *  )
     * @SWG\Parameter(
     *      type="email",
     *      name="email",
     *      in="path",
     *      required=true,
     *      description="User email",
     *      default="User email",
     *  )
     * @SWG\Parameter(
     *      type="password",
     *      name="plainPassword",
     *      in="path",
     *      required=true,
     *      description="User password",
     *      default="User password",
     *  )
     *
     * @Rest\Post("/users", name="create_user")
     */
    public function createAction(Request $request)
    {
        // if need only one route check for role
//        if ($this->getUser()->isGranted('ROLE_ADMIN')) {
//            return $this->handleView($this->view('Access denied', Response::HTTP_FORBIDDEN));
//        }

        $um = $this->get('fos_user.user_manager');
        $user = $um->createUser();

        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->handleView($this->view(['errors' => $form->getErrors()], Response::HTTP_BAD_REQUEST));
        }

        $user->setEnabled(true);
        $um->updateUser($user);

        return $this->handleView($this->view($user, Response::HTTP_OK));
    }

    /**
     * @Rest\Delete("/users/{id}", name="delete_user")
     */
    public function deleteAction(User $user)
    {
        $um = $this->get('fos_user.user_manager');

        $um->deleteUser($user);

        return $this->handleView($this->view('User deleted successful', Response::HTTP_OK));
    }

    /**
     * @Rest\Post("/users/{user}/group/{group}", name="add_user_to_group")
     */
    public function addToGroupAction(User $user, Group $group)
    {
        $um = $this->get('fos_user.user_manager');

        if($user->addGroup($group)) {
            $um->updateUser($user);

            return $this->handleView($this->view('User assign to group successfull', Response::HTTP_OK));
        }

        return $this->handleView($this->view('User exist in a group', Response::HTTP_OK));
    }

    /**
     * @Rest\Delete("/users/{user}/group/{group}", name="delete_user_from_group")
     */
    public function deleteFromGroupAction(User $user, Group $group)
    {
        $um = $this->get('fos_user.user_manager');

        if($user->removeGroup($group)){
            $um->updateUser($user);

            return $this->handleView($this->view('User remove from group successfull', Response::HTTP_OK));
        }

        return $this->handleView($this->view('User is not in a group', Response::HTTP_OK));
    }
}
