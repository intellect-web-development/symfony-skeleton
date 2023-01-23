<?php

declare(strict_types=1);

namespace App\Common\Entry\Http\Admin\Menu;

class SectionBuilderFactory
{
    /** @var SectionBuilderInterface[] */
    private array $sectionBuilders = [];

    /**
     * @param SectionBuilderInterface[]|iterable $sectionBuilders
     */
    public function __construct(
        iterable $sectionBuilders,
    ) {
        foreach ($sectionBuilders as $sectionBuilder) {
            $this->sectionBuilders[] = $sectionBuilder;
        }
    }

    /**
     * @return SectionBuilderInterface[]
     */
    public function getSortedBuilders(): array
    {
        $builders = [];
        foreach ($this->sectionBuilders as $sectionBuilder) {
            $builders[$sectionBuilder->getOrder()] = $sectionBuilder;
        }
        sort($builders);

        return $builders;
    }
}
