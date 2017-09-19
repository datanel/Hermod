<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Model\Api\Json\Document\StopPoint as StopPointDocument;

class StopPointManager
{
    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findOrCreate(StopPointDocument $stopPoint)
    {
        $stopPointEntity = $this->em->getRepository('AppBundle:StopPoint')
            ->findOrCreate($stopPoint->getCode(), $stopPoint->getSource()->getName())
        ;

        $stopPointEntity
            ->setName($stopPoint->getName())
            ->setRouteId($stopPoint->getRoute()->getId())
            ->setRouteName($stopPoint->getRoute()->getName())
            ->setCode($stopPoint->getCode())
            ->setSourceName($stopPoint->getSource()->getName())
        ;
        $this->em->persist($stopPointEntity);

        return $stopPointEntity;
    }
}