<?php

namespace Louvre\TicketBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TicketRepository extends EntityRepository
{
    public function getNbTicket($date)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->select('COUNT(a)')->where('a.date = :date')->setParameter('date', $date);


        // On n'ajoute pas de critère ou tri particulier, la construction
        // de notre requête est finie

        // On récupère la Query à partir du QueryBuilder
        $query = $qb->getQuery();

        // On récupère les résultats à partir de la Query
        $results = $query->getResult();

        // On retourne ces résultats
        return $results;
    }
}