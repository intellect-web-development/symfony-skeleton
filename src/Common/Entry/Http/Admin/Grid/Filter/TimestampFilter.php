<?php

namespace App\Common\Entry\Http\Admin\Grid\Filter;

use DateTime;
use Exception;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

class TimestampFilter implements FilterInterface
{
    /**
     * @throws Exception
     */
    public function apply(DataSourceInterface $dataSource, string $name, mixed $data, array $options = []): void
    {
        if (!$data['dateAt']) {
            return;
        }

        $dataSource->restrict(
            $dataSource
                ->getExpressionBuilder()
                ->andX(
                    $dataSource->getExpressionBuilder()->greaterThanOrEqual(
                        $name,
                        (new DateTime($data[$name]))->getTimestamp()
                    ),
                    $dataSource->getExpressionBuilder()->lessThanOrEqual(
                        $name,
                        (new DateTime($data[$name]))->modify('tomorrow -1 second')->getTimestamp()
                    ),
                )
        );
    }
}
