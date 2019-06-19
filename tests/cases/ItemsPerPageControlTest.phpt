<?php declare(strict_types=1);

namespace Tests\Surda\ItemsPerPage;

use Nette\DI\Container;
use Surda\ItemsPerPage\ItemsPerPageControl;
use Surda\ItemsPerPage\ItemsPerPageFactory;
use Surda\KeyValueStore\ArrayStore;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class ItemsPerPageControlTest extends TestCase
{
    public function testControl()
    {
        $config = [
            'itemsPerPage' => [
                'storage' => ArrayStore::class
            ]
        ];

        /** @var Container $container */
        $container = (new ContainerFactory())->create([
            'itemsPerPage' => [
                'storage' => ArrayStore::class
            ]
        ],1);

//        $container = $this->createContainer($config);

        /** @var ItemsPerPageFactory $factory */
        $factory = $container->getService('itemsPerPage.factory');

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