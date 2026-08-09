<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Survos\FieldBundle\Repository\QueryBuilderHelperInterface;
use Survos\FieldBundle\Repository\QueryBuilderHelperTrait;

final class CarRepository extends ServiceEntityRepository implements QueryBuilderHelperInterface
{
    use QueryBuilderHelperTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }
}
