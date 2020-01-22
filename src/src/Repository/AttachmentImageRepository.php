<?php

namespace App\Repository;

use App\Entity\AttachmentImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AttachmentImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttachmentImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttachmentImage[]    findAll()
 * @method AttachmentImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttachmentImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttachmentImage::class);
    }

    // /**
    //  * @return AttachmentImage[] Returns an array of AttachmentImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AttachmentImage
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
