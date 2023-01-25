<?php

namespace App\Common\Entry\Http\Admin\Grid\Filter;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

class Select2Filter implements FilterInterface
{
    public function apply(DataSourceInterface $dataSource, string $name, mixed $data, array $options = []): void
    {
        if (empty($data[$name])) {
            return;
        }

        $dataSource->restrict($dataSource->getExpressionBuilder()->equals($name, $data[$name]));
    }
}
