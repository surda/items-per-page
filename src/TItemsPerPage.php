<?php declare(strict_types=1);

namespace Surda\ItemsPerPage;

/**
 * @deprecated
 */
trait TItemsPerPage
{
    /** @var ItemsPerPageFactory */
    private $itemsPerPageFactory;

    /**
     * @param ItemsPerPageFactory $itemsPerPageFactory
     */
    public function injectItemsPerPageFactory(ItemsPerPageFactory $itemsPerPageFactory): void
    {
        $this->itemsPerPageFactory = $itemsPerPageFactory;
    }

    /**
     * @return ItemsPerPageControl
     */
    protected function createComponentIpp(): ItemsPerPageControl
    {
        // Init items per page component
        $control = $this->itemsPerPageFactory->create();

        // Define event
        $control->onChange[] = function (ItemsPerPageControl $control, int $value): void {
            $this->redirect('this');
        };

        return $control;
    }
}