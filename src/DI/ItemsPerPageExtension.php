<?php declare(strict_types=1);

namespace Surda\ItemsPerPage\DI;

use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;
use Surda\ItemsPerPage\ItemsPerPageFactory;
use Surda\ItemsPerPage\Storage\Session;

class ItemsPerPageExtension extends CompilerExtension
{
    /** @var array */
    public $defaults = [
        'values' => [],
        'defaultValue' => 20,
        'useAjax' => TRUE,
        'template' => NULL,
        'templates' => [],
        'storage' => NULL,
    ];

    /** @var array */
    private $values = [20, 50, 100];

    /** @var array */
    private $templates = [
        'default' => __DIR__ . '/../Templates/bootstrap4.dropdown.latte',
        'dropdown-sm' => __DIR__ . '/../Templates/bootstrap4.dropdown.sm.latte',
        'dropdown-danger' => __DIR__ . '/../Templates/bootstrap4.dropdown.danger.latte',
        'dropdown-sm-danger' => __DIR__ . '/../Templates/bootstrap4.dropdown.sm.danger.latte',
        'nav-item' => __DIR__ . '/../Templates/bootstrap4.nav-item.latte',
    ];

    public function loadConfiguration(): void
    {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults);

        $this->validate($config);

        $storage = $builder->addDefinition($this->prefix('storage'));
        if ($config['storage'] === NULL) {
            $storage->setFactory(Session::class, ['Surda\ItemsPerPage']);
        } else {
            $storage->setFactory($config['storage']);
        }

        $itemsPerPage = $builder->addDefinition($this->prefix('itemsPerPage'))
            ->setImplement(ItemsPerPageFactory::class)
            ->addSetup('setAvailableValues', [$config['values'] === [] ? $this->values : $config['values']])
            ->addSetup('setDefaultValue', [$config['defaultValue']])
            ->addSetup($config['useAjax'] === TRUE ? 'enableAjax' : 'disableAjax');

        $templates = $config['templates'] === [] ? $this->templates : $config['templates'];
        foreach ($templates as $type => $templateFile) {
            $itemsPerPage->addSetup('setTemplateByType', [$type, $templateFile]);
        }

        if ($config['template'] !== NULL) {
            $itemsPerPage->addSetup('setTemplate', [$config['template']]);
        }
    }

    /**
     * @param array $config
     */
    private function validate(array $config): void
    {
        Validators::assertField($config, 'values', 'array');
        Validators::assertField($config, 'defaultValue', 'int');
        Validators::assertField($config, 'useAjax', 'bool');
        Validators::assertField($config, 'template', 'string|null');
        Validators::assertField($config, 'templates', 'array');
        Validators::assertField($config, 'storage', 'null|string');
    }
}