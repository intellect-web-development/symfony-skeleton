<?php

declare(strict_types=1);

namespace App\Common\Entry\Http\Admin\Grid\Filter;

use App\Common\Entry\Http\Admin\Form\Filter\IntegerFilterType;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(
    name: 'sylius.grid_filter',
    attributes: [
        'type' => 'int',
        'form_type' => IntegerFilterType::class,
    ]
)]
class IntegerFilter implements FilterInterface
{
    public const NAME = 'string';
    public const TYPE_EQUAL = 'equal';
    public const TYPE_NOT_EQUAL = 'not_equal';
    public const TYPE_EMPTY = 'empty';
    public const TYPE_NOT_EMPTY = 'not_empty';
    public const TYPE_CONTAINS = 'contains';
    public const TYPE_NOT_CONTAINS = 'not_contains';
    public const TYPE_STARTS_WITH = 'starts_with';
    public const TYPE_ENDS_WITH = 'ends_with';
    public const TYPE_IN = 'in';
    public const TYPE_NOT_IN = 'not_in';

    public function apply(DataSourceInterface $dataSource, string $name, mixed $data, array $options = []): void
    {
        $expressionBuilder = $dataSource->getExpressionBuilder();

        $value = is_array($data) ? $data['value'] ?? null : $data;
        $type = $data['type'] ?? ($options['type'] ?? self::TYPE_CONTAINS);
        $fields = $options['fields'] ?? [$name];

        if (!in_array($type, [self::TYPE_NOT_EMPTY, self::TYPE_EMPTY], true) && '' === trim((string) $value)) {
            return;
        }

        if (1 === count($fields)) {
            $dataSource->restrict($this->getExpression($expressionBuilder, $type, current($fields), (int) $value));

            return;
        }

        $expressions = [];
        foreach ($fields as $field) {
            $expressions[] = $this->getExpression($expressionBuilder, $type, $field, (int) $value);
        }

        if (self::TYPE_NOT_EQUAL === $type) {
            $dataSource->restrict($expressionBuilder->andX(...$expressions));

            return;
        }

        $dataSource->restrict($expressionBuilder->orX(...$expressions));
    }

    private function getExpression(
        ExpressionBuilderInterface $expressionBuilder,
        string $type,
        string $field,
        mixed $value
    ): mixed {
        switch ($type) {
            case self::TYPE_EQUAL:
                return $expressionBuilder->equals($field, $value);
            case self::TYPE_NOT_EQUAL:
                return $expressionBuilder->notEquals($field, $value);
            case self::TYPE_EMPTY:
                return $expressionBuilder->isNull($field);
            case self::TYPE_NOT_EMPTY:
                return $expressionBuilder->isNotNull($field);
            case self::TYPE_CONTAINS:
                return $expressionBuilder->like($field, '%' . $value . '%');
            case self::TYPE_NOT_CONTAINS:
                return $expressionBuilder->notLike($field, '%' . $value . '%');
            case self::TYPE_STARTS_WITH:
                return $expressionBuilder->like($field, $value . '%');
            case self::TYPE_ENDS_WITH:
                return $expressionBuilder->like($field, '%' . $value);
            case self::TYPE_IN:
                return $expressionBuilder->in($field, array_map('trim', explode(',', $value)));
            case self::TYPE_NOT_IN:
                return $expressionBuilder->notIn($field, array_map('trim', explode(',', $value)));
            default:
                throw new \InvalidArgumentException(sprintf('Could not get an expression for type "%s"!', $type));
        }
    }
}
