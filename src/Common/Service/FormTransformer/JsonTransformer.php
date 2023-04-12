<?php

declare(strict_types=1);

namespace App\Common\Service\FormTransformer;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class JsonTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * @param array|null $value
     */
    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        return $this->serializer->serialize($value, 'json');
    }

    /**
     * @param string|null $value
     * @return array|null
     */
    public function reverseTransform($value): ?array
    {
        // no issue number? It's optional, so that's ok
        if (!$value) {
            return null;
        }

        return json_decode($value, true);
    }
}
