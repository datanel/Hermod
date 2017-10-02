<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Query\Expr;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

/**
 * LocationPatchRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LocationPatchRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByPeriod(string $startDate, string $endDate) : array
    {
        $equipment = 'AppBundle\Entity\StopPoint';
        $qb = $this->createQueryBuilder('lp');
        $query = $qb
            ->select('lp, u, e.name AS equipment_name, e.code AS equipment_code, e.sourceName AS equipment_source_name')
            ->from($equipment, 'e')
            ->where($qb->expr()->gte('lp.createdAt', ':startDate'))
            ->andWhere($qb->expr()->lt('lp.createdAt', ':endDate'))
            ->andWhere($qb->expr()->eq('lp.equipmentId', 'e.id'))
            ->join('lp.user', 'u', Expr\Join::WITH, $qb->expr()->eq('u.id', 'lp.user'))
            ->orderBy('lp.createdAt', 'DESC')
            ->setParameters(new ArrayCollection([
                new Parameter('startDate', $startDate),
                new Parameter('endDate', $endDate)
            ]))
            ->getQuery()
        ;

        return $query->getResult();
    }
}
