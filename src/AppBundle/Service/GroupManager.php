<?php

namespace AppBundle\Service;

use AppBundle\Entity\Group;
use Doctrine\ORM\EntityManager;


/**
 * Class GroupManager
 * @package App\Service
 */
class GroupManager
{
    /**
     * @var
     */
    protected $em;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repo;

    /**
     * UserManager constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository($this->getClass());
    }

    /**
     * @param Group $group
     * @return Group
     * @throws \Exception
     */
    public function save(Group $group)
    {
        try {
            $this->em->persist($group);
            $this->em->flush();

            return $group;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $obj
     * @return bool
     * @throws \Exception
     */
    public function remove($obj)
    {
        try {
            $this->em->remove($obj);
            $this->em->flush();

            return true;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getById($id)
    {
        return $this->repo->find($id);
    }


    /**
     * Get class name
     * @return string
     */
    protected function getClass()
    {
        return Group::class;
    }

}
