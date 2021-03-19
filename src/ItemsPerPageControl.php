<?php declare(strict_types=1);

namespace Surda\ItemsPerPage;

use Nette\Application\UI\Template;
use Surda\ItemsPerPage\Exception\InvalidArgumentException;
use Surda\KeyValueStorage\KeyValueStorage;
use Surda\UI\Control\ThemeableControls;
use Nette\Application\UI;

/**
 * @method onChange(ItemsPerPageControl $param, int $value)
 */
class ItemsPerPageControl extends UI\Control
{
    use ThemeableControls;

    /** @var KeyValueStorage */
    protected $storage;

    /** @var array<int, int> */
    protected $availableValues = [];

    /** @var int */
    protected $defaultValue;

    /** @var bool */
    protected $useAjax = TRUE;

    /** @var string */
    protected $storageKeyName;

    /** @var array<mixed> */
    public $onChange;

    /**
     * @param KeyValueStorage $storage
     */
    public function __construct(KeyValueStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param string $templateType
     */
    public function render(string $templateType = 'default'): void
    {
        /** @var Template $template */
        $template = $this->template;
        $template->setFile($this->getTemplateByType($templateType));

        $template->availableValues = $this->getAvailableValues();
        $template->value = $this->getValue();
        $template->useAjax = $this->useAjax;

        $template->render();
    }

    /**
     * @param int $value
     */
    public function handleChange(int $value): void
    {
        $this->setValue($value);

        if ($this->useAjax) {
            $this->redrawControl('ItemsPerPageSnippet');
        }

        $this->onChange($this, $value);
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        $value = (int)$this->storage->read($this->getStorageKeyName(), $this->defaultValue);

        if (!$this->isValidValue($value)) {
            return $this->defaultValue;
        }

        return $value;
    }


    /**
     * @param int $value
     * @throws InvalidArgumentException
     */
    public function setValue(int $value): void
    {
        if (!$this->isValidValue($value)) {
            $value = $this->defaultValue;
        }

        $this->storage->write($this->getStorageKeyName(), (string)$value);
    }

    /**
     * @param int $defaultValue
     * @throws InvalidArgumentException
     */
    public function setDefaultValue(int $defaultValue): void
    {
        if (!$this->isValidValue($defaultValue)) {
            throw new InvalidArgumentException("Invalid argument 'defaultValue'.");
        }

        $this->defaultValue = $defaultValue;
    }

    /**
     * @param array<mixed> $availableValues
     * @throws InvalidArgumentException
     */
    public function setAvailableValues(array $availableValues): void
    {
        $values = [];
        foreach ($availableValues as $key => $value) {
            if (is_int($value)) {
                $values[$value] = $value;
            } else {
                $values[$key] = $value;
            }
        }

        $this->availableValues = $values;
    }

    /**
     * @return array<mixed>
     */
    public function getAvailableValues(): array
    {
        return $this->availableValues;
    }

    public function enableAjax(): void
    {
        $this->useAjax = TRUE;
    }

    public function disableAjax(): void
    {
        $this->useAjax = FALSE;
    }

    /**
     * @return string
     */
    public function getStorageKeyName(): string
    {
        return $this->storageKeyName;
    }

    /**
     * @param string $storageKeyName
     */
    public function setStorageKeyName(string $storageKeyName): void
    {
        $this->storageKeyName = $storageKeyName;
    }


    /**
     * @param int $value
     * @return bool
     */
    protected function isValidValue(int $value): bool
    {
        return array_key_exists($value, $this->getAvailableValues()) ? TRUE : FALSE;
    }
}