<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer\Path;

final class Path
{
    /**
     * @psalm-var array<int, array-key>
     */
    private $pathElements;

    /**
     * @param array<int, array-key> $pathElements
     */
    public function __construct(array $pathElements)
    {
        $this->pathElements = $pathElements;
    }

    /**
     * @return mixed|null
     */
    public function &expose(array &$array)
    {
        $null         = null;
        $pathElements = $this->pathElements;

        if ($pathElements === []) {

            return $array;
        }

        foreach ($pathElements as $element) {
            if (isset($array[$element]) === false) {
                return $null;
            }

            /** @var mixed $array */
            $array = &$array[$element];
        }

        return $array;
    }

    /**
     * @return array<int, self>
     */
    public function unwrap(array $array): array
    {
        $bracketsPathIndex = \array_search('[]', $this->pathElements, true);
        if ($bracketsPathIndex === false) {
            return [new self($this->pathElements)];
        }

        $headPath = $this->slice(0, $bracketsPathIndex);

        /** @var mixed|array $currentArray */
        $currentArray = $headPath->expose($array);

        if (\is_array($currentArray) === false) {
            throw new \LogicException(
                \sprintf('There is no array at path %s', (string)$headPath)
            );
        }

        $tailPath = $this->slice($bracketsPathIndex + 1, null);

        $newPaths = [];
        foreach (\array_keys($currentArray) as $key) {
            $itemPath = $tailPath->prepend(new self([$key]));
            foreach ($itemPath->unwrap($currentArray) as $transformed) {
                $newPaths[] = $headPath->append($transformed);
            }
        }

        return $newPaths;
    }

    public function __toString(): string
    {
        return \implode('.', $this->pathElements);
    }

    public function previousPath(): self
    {
        return new self(
            \array_slice($this->pathElements, 0, -1)
        );
    }

    public function prependForArray(): self
    {
        return $this->prepend(new Path(['[]']));
    }

    private function slice(int $offset, ?int $length): self
    {
        return new self(
            \array_slice($this->pathElements, $offset, $length)
        );
    }

    private function prepend(self $path): self
    {
        return new self([...$path->pathElements, ...$this->pathElements]);
    }

    private function append(self $path): self
    {
        return new self([...$this->pathElements, ...$path->pathElements]);
    }
}
