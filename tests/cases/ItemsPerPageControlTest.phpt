<?php declare(strict_types=1);

namespace Tests\Surda\ItemsPerPage;

use Nette\DI\Container;
use Surda\ItemsPerPage\ItemsPerPageControl;
use Surda\ItemsPerPage\ItemsPerPageFactory;
use Surda\ItemsPerPage\Storage\NullStorage;
use Testbench\TComponent;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../bootstrap.testbench.php';

/**
 * @testCase
 */
class ItemsPerPageControlTest extends TestCase
{
    use TComponent;

    public function testControl()
    {
        /** @var Container $container */
        $container = (new ContainerFactory())->create([
            'itemsPerPage' => [
                'storage' => NullStorage::class
            ]
        ],1);

        /** @var ItemsPerPageFactory $factory */
        $factory = $container->getService('itemsPerPage.itemsPerPage');

        /** @var ItemsPerPageControl $control */
        $control = $factory->create();

        Assert::same(20, $control->getValue());
        Assert::same([20 => 20, 50 => 50, 100 => 100], $control->getAvailableValues());

        $control->setAvailableValues([10, 20, 30]);
        $control->setValue(10);
        Assert::same(10, $control->getValue());
        Assert::same([10 => 10, 20 => 20, 30 => 30], $control->getAvailableValues());

        $control->setAvailableValues([10 => 'ten', 20 => 'twenty', 30 => 'thirty']);
        Assert::same([10 => 'ten', 20 => 'twenty', 30 => 'thirty'], $control->getAvailableValues());
    }
}

(new ItemsPerPageControlTest())->run();