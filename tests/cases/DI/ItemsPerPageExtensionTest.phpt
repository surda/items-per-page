<?php declare(strict_types=1);

namespace Tests\Surda\ItemsPerPage;

use Surda\ItemsPerPage\ItemsPerPageFactory;
use Surda\ItemsPerPage\Storage\NullStorage;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
class ItemsPerPageExtensionTest extends TestCase
{
    public function testRegistration()
    {
        /** @var Container $container */
        $container = (new ContainerFactory())->create([
            'itemsPerPage' => [
                'storage' => NullStorage::class
            ]
        ]);

        /** @var ItemsPerPageFactory $factory */
        $factory = $container->getService('itemsPerPage.itemsPerPage');
        Assert::true($factory instanceof ItemsPerPageFactory);

        /** @var ItemsPerPageFactory $factory */
        $factory = $container->getByType(ItemsPerPageFactory::class);
        Assert::true($factory instanceof ItemsPerPageFactory);
    }
}

(new ItemsPerPageExtensionTest())->run();