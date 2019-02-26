<?php declare(strict_types=1);

namespace Surda\ItemsPerPage;

interface ItemsPerPageFactory
{
    /**
     * @return ItemsPerPageControl
     */
    public function create(): ItemsPerPageControl;
}
