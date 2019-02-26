# Items per page

## Installation

The recommended way to is via Composer:

```
composer require surda/items-per-page
```

After that you have to register extension in config.neon:

```yaml
extensions:
    itemsPerPage: Surda\ItemsPerPage\ItemsPerPageExtension
```

## Configuration

Default
```yaml
itemsPerPage:
    listOfValues: [20, 50, 100]
    defaultValue: 20
    useAjax: TRUE
    templateFile: bootstrap4.latte
    storage: Surda\ItemsPerPage\Storage\Session
```

## Usage

Presenter

```php
use Surda\ItemsPerPage\ItemsPerPageControl;

class ProductPresenter extends Nette\Application\UI\Presenter
{
    use ItemsPerPage;

    public function actionDefault(): void
    {
        /** @var ItemsPerPageControl $ipp */
        $ipp = $this->getComponent('ipp');

        $itemsPerPage = $ipp->getValue();
    }
}
```
Template

```html
{control ipp}
```

### Custom

```php
use Surda\ItemsPerPage\ItemsPerPageControl;
use Surda\ItemsPerPage\ItemsPerPageFactory;

class ProductPresenter extends Nette\Application\UI\Presenter
{
    /** @var ItemsPerPageFactory */
    private $itemsPerPageFactory;

    /**
     * @param ItemsPerPageFactory $itemsPerPageFactory
     */
    public function injectItemsPerPage(ItemsPerPageFactory $itemsPerPageFactory): void
    {
        $this->itemsPerPageFactory = $itemsPerPageFactory;
    }

    public function actionDefault(): void
    {
        /** @var ItemsPerPageControl $ipp */
        $ipp = $this->getComponent('ipp');

        $itemsPerPage = $ipp->getValue();
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
            // ...
        };

        return $control;
    }
}
```

## Custom options

```php
class ProductPresenter extends Nette\Application\UI\Presenter
{
    /**
     * @return ItemsPerPageControl
     */
    protected function createComponentIpp(): ItemsPerPageControl
    {
        // Init items per page component
        $control = $this->itemsPerPageFactory->create();
        
        // list of allowed values 
        $control->setListOfValues([20, 50, 100]);

        // Default items per page
        $control->setDefaultValue(20);

        // Value of items per page
        $control->setValue(20);

        // To use your own template
        $control->setTemplateFile('path/to/your/latte/file.latte');

        // Enable ajax (defult is enabled)
        $control->enableAjax();
        
        // Disable ajax
        $control->disableAjax();
        
        return $control;
    }
}
```