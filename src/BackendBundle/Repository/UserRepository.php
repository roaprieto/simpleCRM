<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 10.10.16
 * Time: 19:37
 */

namespace BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAllOrCriteria($criteria)
    {

    }
}