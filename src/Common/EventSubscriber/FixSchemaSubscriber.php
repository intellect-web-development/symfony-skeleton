<?php

declare(strict_types=1);

namespace App\Common\EventSubscriber;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Doctrine\ORM\Tools\ToolEvents;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/** @codeCoverageIgnore */
#[AutoconfigureTag(
    name: 'doctrine.event_listener',
    attributes: ['event' => ToolEvents::postGenerateSchema],
)]
class FixSchemaSubscriber
{
    /** @throws SchemaException */
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schema = $args->getSchema();
        if (!$schema->hasNamespace('public')) {
            $schema->createNamespace('public');
        }
    }
}
