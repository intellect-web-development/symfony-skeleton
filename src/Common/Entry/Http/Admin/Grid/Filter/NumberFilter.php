<?php

namespace App\Common\Entry\Http\Admin\Grid\Filter;

use Exception;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

class NumberFilter implements FilterInterface
{
    /**
     * @throws Exception
     */
    public function apply(DataSourceInterface $dataSource, string $name, mixed $data, array $options = []): void
    {
        $value = trim($data);

        if ('' === $value) {
            return;
        }

        $dataSource->restrict(
            $dataSource
                ->getExpressionBuilder()
                ->andX(
                    $dataSource->getExpressionBuilder()->equals(
                        $name,
                        str_replace(',', '.', $value)
                    ),
                )
        );
    }
}
