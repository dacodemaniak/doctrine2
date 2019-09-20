<?php
namespace src\Repositories;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Entities\Sessions;

class SessionRepository extends EntityRepository
{

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
    }
    
    // Your methods here...
    public function findByYear(\DateTime $year) {
        $newYearsDay = new \DateTime($year->format("Y") . "-01-01");
        $sylvesterDay = clone $newYearsDay;
        $sylvesterDay->modify("+1 year")->modify("-1 day");
        
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('s')
            ->from(Sessions::class, 's')
            ->where('s.beginAt BETWEEN :year AND :endYear')
            ->setParameter('year', $newYearsDay)
            ->setParameter('endYear', $sylvesterDay);
        $query = $queryBuilder->getQuery();
        
        return $query->execute();
    }
    
    public function findById(int $id) {
        return $this->find($id);
    }
}

