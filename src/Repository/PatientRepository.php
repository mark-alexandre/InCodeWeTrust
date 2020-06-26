<?php

namespace App\Repository;

use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Patient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Patient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Patient[]    findAll()
 * @method Patient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    /**
     * @param int $id
     * @return Patient[] Returns an array of Patient objects
     */




    /*
    public function findOneBySomeField($value): ?Patient
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param int $id
     */

    public function addDoctor(EntityManagerInterface $em, $idDoc, $idPatient) {
        return $em->createQuery(
            'UPDATE App\Entity\Patient p SET p.doctor ='.$idDoc.' WHERE p.id = '.$idPatient)->execute();


    }



    /**
     * @param int $id
     * @return Patient[] Returns an array of Patient objects
     */

    public function removeDoctor(int $id)
    {
        return $this->createQueryBuilder('p')
            ->update(Patient::class,'p')
            ->set('p.doctor', NULL)
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute()
            ;
    }
}
