<?php

declare(strict_types=1);

namespace App\Search;

use App\Entity\Product;
use Doctrine\ORM\QueryBuilder;
use Mezcalito\UxSearchBundle\Adapter\Doctrine\DoctrineAdapter;
use Mezcalito\UxSearchBundle\Attribute\AsSearch;
use Survos\SearchBundle\Search\AbstractFieldSearch;

#[AsSearch(index: Product::class, adapter: 'default')]
final class ProductSearch extends AbstractFieldSearch
{
    protected function getFieldClass(array $options = []): string
    {
        return Product::class;
    }

    public function build(array $options = []): void
    {
        parent::build($options);

        $this
            ->setAdapterParameters(array_replace($this->getAdapterParameters(), [
                DoctrineAdapter::SEARCH_FIELDS => ['o.title', 'o.description'],
                DoctrineAdapter::QUERY_BUILDER_ALIAS => 'o',
                DoctrineAdapter::QUERY_BUILDER => static function (QueryBuilder $qb): void {
                    $qb->andWhere('o.title IS NOT NULL');
                },
            ]))
            ->addAvailableSort('o.rating:desc', 'Rating')
            ->addAvailableSort('o.stock:desc', 'Stock')
            ->addAvailableSort('o.exactPrice:asc', 'Price low to high')
            ->addAvailableSort('o.exactPrice:desc', 'Price high to low');
    }
}
