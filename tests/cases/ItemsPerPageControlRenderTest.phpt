<?php declare(strict_types=1);

namespace Tests\Surda\ItemsPerPage;

use Nette\DI\Container;
use Surda\ItemsPerPage\ItemsPerPageControl;
use Surda\ItemsPerPage\ItemsPerPageFactory;
use Surda\ItemsPerPage\Storage\NullStorage;
use Testbench\TComponent;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../bootstrap.testbench.php';

/**
 * @testCase
 * @skip
 */
class ItemsPerPageControlRenderTest extends TestCase
{
    use TComponent;

    public function testRender()
    {
        /** @var Container $container */
        $container = (new ContainerFactory())->create([
            'itemsPerPage' => [
                'storage' => NullStorage::class,
                'template' => __DIR__ . '/files/default.latte',
            ],
        ], 2);

        /** @var ItemsPerPageFactory $factory */
        $factory = $container->getService('itemsPerPage.itemsPerPage');

        /** @var ItemsPerPageControl $control */
        $control = $factory->create();

        $this->checkRenderOutput($control, 'availableValues [20 => 20, 50 => 50, 100 => 100], value 20');
    }
}

(new ItemsPerPageControlRenderTest())->run();