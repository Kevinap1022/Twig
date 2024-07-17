<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\Runtime;

/**
 * Represents a for loop context variable.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @internal
 */
final class LoopContext
{
    private mixed $lastChanged;

    public function __construct(private LoopIterator $loop, private $parent)
    {
    }

    public function getParent(): mixed
    {
        return $this->parent;
    }

    public function getRevindex0(): int
    {
        return $this->loop->getLength('revindex0') - $this->getIndex();
    }

    public function getRevindex(): int
    {
        return $this->loop->getLength('revindex') - $this->getIndex0();
    }

    public function getIndex0(): int
    {
        return $this->loop->getIndex0();
    }

    public function getIndex(): int
    {
        return $this->getIndex0() + 1;
    }

    public function getLength(): int
    {
        return $this->loop->getLength();
    }

    public function isFirst(): bool
    {
        return 0 === $this->getIndex0();
    }

    public function isLast(): bool
    {
        return $this->loop->isLast();
    }

    public function hasChanged(mixed $value): bool
    {
        if (!isset($this->lastChanged) || $value !== $this->lastChanged) {
            $this->lastChanged = $value;

            return true;
        }

        return false;
    }

    public function getPrevious(): mixed
    {
        $previous = $this->loop->getPrevious();

        return $previous['valid'] ? $previous['value'] : null;
    }

    public function getNext(): mixed
    {
        $next = $this->loop->getNext();

        return $next['valid'] ? $next['value'] : null;
    }

    public function cycle($value, ...$values): mixed
    {
        array_unshift($values, $value);

        return $values[$this->getIndex0() % count($values)];
    }
}