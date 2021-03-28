<?php

declare(strict_types=1);

namespace BEAR\ApiDoc;

use ReflectionParameter;

use function sprintf;

final class DocParam
{
    /**
     * @var string
     * @readonly
     */
    private $name;

    /**
     * @var string
     * @readonly
     */
    private $type;

    /**
     * @var string
     * @readonly
     */
    private $descripton;

    /**
     * @var bool
     * @readonly
     */
    private $isOptional;

    /** @var string  */
    private $default = '';

    /** @var string  */
    private $example = '';

    /** @var SchemaConstrains */
    private $constaints;

    public function __construct(
        ReflectionParameter $parameter,
        TagParam $tagParam,
        ?SchemaProp $prop
    ) {
        $this->name = $parameter->name;
        $this->type = (string) $parameter->getType() ?: $tagParam->type;
        $this->isOptional = $parameter->isOptional();
        $this->default = $parameter->isDefaultValueAvailable() ? (string) $parameter->getDefaultValue() : '';
        $this->descripton = $tagParam->description;
        $this->example = $prop->example ?? '';
        if ($prop) {
            $this->setByProp($prop);
        }
    }

    private function setByProp(SchemaProp $prop): void
    {
        $this->constaints = $prop->constrains;
        if (! $this->descripton) {
            /** @psalm-suppress InaccessibleProperty */
            $this->descripton = $prop->descripton;
        }
    }

    public function __toString()
    {
        $requred = $this->isOptional ? 'Optional' : 'Required';

        return sprintf('| %s | %s | %s | %s | %s | %s |', $this->name, $this->type, $this->descripton, $this->default, $this->constaints, $this->example);
    }
}
