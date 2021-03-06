<?php

namespace OfferBundle\Repository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{
    public function deleteComment($article){
        $query = $this
            ->createQueryBuilder('c')
            ->delete('OfferBundle:Comment', 'c')
            ->where('c.article = :article')
            ->setParameter('article', $article)
            ->getQuery();
        return $query->execute();
    }
}
