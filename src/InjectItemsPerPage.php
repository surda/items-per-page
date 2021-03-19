<?php declare(strict_types=1);

namespace Surda\ItemsPerPage;

trait InjectItemsPerPage
{
    /** @var ItemsPerPageFactory */
    private $itemsPerPageFactory;

    /** @var int */
    private $itemsPerPage;

    /**
     * @param ItemsPerPageFactory $itemsPerPageFactory
     */
    public function injectItemsPerPageFactory(ItemsPerPageFactory $itemsPerPageFactory): void
    {
        $this->itemsPerPageFactory = $itemsPerPageFactory;

        $this->onStartup[] = function () {
            /** @var ItemsPerPageControl $ipp */
            $ipp = $this->getComponent('ipp');

            $this->itemsPerPage = $ipp->getValue();
        };
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
            $this->redirect('this', ['vp-page' => NULL]);
        };

        return $control;
    }
}