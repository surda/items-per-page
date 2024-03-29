<?php declare(strict_types=1);

namespace Surda\ItemsPerPage\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Surda\ItemsPerPage\ItemsPerPageControl;
use Surda\ItemsPerPage\ItemsPerPageFactory;
use Surda\KeyValueStorage\SessionStorage;
use stdClass;

/**
 * @property-read stdClass $config
 */
class ItemsPerPageExtension extends CompilerExtension
{
    /** @var array<int, int> */
    private $values = [20, 50, 100];

    /** @var array<string, string> */
    private $templates = [
        'default' => __DIR__ . '/../Templates/bootstrap4.dropdown.latte',
        'dropdown-sm' => __DIR__ . '/../Templates/bootstrap4.dropdown.sm.latte',
        'dropdown-danger' => __DIR__ . '/../Templates/bootstrap4.dropdown.danger.latte',
        'dropdown-sm-danger' => __DIR__ . '/../Templates/bootstrap4.dropdown.sm.danger.latte',
        'nav-item' => __DIR__ . '/../Templates/bootstrap4.nav-item.latte',
    ];

    public function getConfigSchema(): Schema
    {
        return Expect::structure([
            'values' => Expect::array()->default([]),
            'defaultValue' => Expect::int()->default(20),
            'storageKeyName' => Expect::string()->default('ipp'),
            'useAjax' => Expect::bool(TRUE),
            'template' => Expect::string()->nullable()->default(NULL),
            'templates' => Expect::array()->default([]),
            'storage' => Expect::anyOf(Expect::string(), Expect::type(Statement::class))->nullable(),
        ]);
    }

    public function loadConfiguration(): void
    {
        $builder = $this->getContainerBuilder();
        $config = $this->config;

        $storage = $builder->addDefinition($this->prefix('storage'));
        if ($config->storage === NULL) {
            $storage->setFactory(SessionStorage::class, [$config->storageKeyName]);
        } else {
            $storage->setFactory($config->storage);
        }

        $itemsPerPageFactory = $builder->addFactoryDefinition($this->prefix('factory'))
            ->setImplement(ItemsPerPageFactory::class);

        $itemsPerPageDefinition = $itemsPerPageFactory->getResultDefinition();
        $itemsPerPageDefinition->setFactory(ItemsPerPageControl::class, [$storage]);

        $itemsPerPageDefinition->addSetup('setAvailableValues', [$config->values === [] ? $this->values : $config->values]);
        $itemsPerPageDefinition->addSetup('setDefaultValue', [$config->defaultValue]);
        $itemsPerPageDefinition->addSetup('setStorageKeyName', [$config->storageKeyName]);
        $itemsPerPageDefinition->addSetup($config->useAjax === TRUE ? 'enableAjax' : 'disableAjax');

        $templates = $config->templates === [] ? $this->templates : $config->templates;

        foreach ($templates as $type => $templateFile) {
            $itemsPerPageDefinition->addSetup('setTemplateByType', [$type, $templateFile]);
        }

        if ($config->template !== NULL) {
            $itemsPerPageDefinition->addSetup('setTemplate', [$config->template]);
        }
    }
}