<?php

namespace OfferBundle\Repository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository
{
    public function getNav(){
        $query = $this
            ->createQueryBuilder('c')
            ->select('c.name')
            ->getQuery();
        return $query->execute();
    }
}
