<?php

declare(strict_types=1);

namespace App\Infrastructure\ArrayComposer;

use App\Infrastructure\ArrayComposer\ResourceLink\ResourceLink;

final class LinkParams
{
    /**
     * @var string
     */
    public $leftResourceId;

    /**
     * @var Path
     */
    public $leftPath;

    /**
     * @var string
     */
    public $rightResourceId;

    /**
     * @var Path
     */
    public $rightPath;

    /**
     * @var ResourceLink
     */
    public $resourceLink;

    /**
     * @var string
     */
    public $writeField;

    public function __construct(
        string $leftResourceId,
        Path $leftPath,
        string $rightResourceId,
        Path $rightPath,
        ResourceLink $resourceLink,
        string $writeField
    )
    {
        $this->leftResourceId  = $leftResourceId;
        $this->leftPath        = $leftPath;
        $this->rightResourceId = $rightResourceId;
        $this->rightPath       = $rightPath;
        $this->resourceLink    = $resourceLink;
        $this->writeField      = $writeField;
    }
}
