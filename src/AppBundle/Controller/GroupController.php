<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Form\GroupType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;

/**
 * Class GroupController
 * @package AppBundle\Controller
 */
class GroupController extends FOSRestController
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
     *      name="name",
     *      in="path",
     *      required=true,
     *      description="Group name",
     *      default="Group name",
     *  )
     * @param Request $request
     * @return Response
     * @Rest\Post("/group", name="create_group")
     */
    public function createAction(Request $request)
    {
        $gm = $this->get('group_manager');
        $group = new Group();

        $form = $this->createForm(GroupType::class, $group);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->handleView($this->view(['errors' => $form->getErrors()], Response::HTTP_BAD_REQUEST));

        }

        $res = $gm->save($group);

        if ($res instanceof Group) {
            return $this->handleView($this->view($res, Response::HTTP_OK));
        }

        return $this->handleView($this->view($res));

    }

    /**
     * @param Group $group
     * @return Response
     * @Rest\Delete("/group/{id}", name="delete_group")
     */
    public function deleteAction(Group $group)
    {
        $gm = $this->get('group_manager');

        if (!$group->isEmpty()) {
            return $this->handleView($this->view('Group has users cannot delete', Response::HTTP_FORBIDDEN));
        }

        $gm->remove($group);

        return $this->handleView($this->view('Group deleted successfull', Response::HTTP_OK));
    }
}
