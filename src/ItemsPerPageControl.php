<?php declare(strict_types=1);

namespace Surda\ItemsPerPage;

use Surda\ComponentHelpers\Traits\Themeable;
use Surda\ItemsPerPage\Exception\InvalidArgumentException;
use Surda\ItemsPerPage\Exception\ValueNotFoundException;
use Surda\ItemsPerPage\Storage\IStorage;
use Nette\Application\UI;

class ItemsPerPageControl extends UI\Control
{
    use Themeable;

    CONST VALUE_KEY_NAME = 'ppi';

    /** @var IStorage */
    protected $storage;

    /** @var array */
    protected $availableValues = [];

    /** @var int */
    protected $defaultValue;

    /** @var bool */
    protected $useAjax = TRUE;

    /** @var array */
    public $onChange;

    /**
     * @param IStorage $storage
     */
    public function __construct(IStorage $storage)
    {
        parent::__construct();

        $this->storage = $storage;
    }

    /**
     * @param string $templateType
     */
    public function render(string $templateType = 'default'): void
    {
        /** @var \Nette\Application\UI\ITemplate $template */
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
        try {
            $value = (int)$this->storage->read(self::VALUE_KEY_NAME);
        }
        catch (ValueNotFoundException $e) {
            return $this->defaultValue;
        }

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

        $this->storage->write(self::VALUE_KEY_NAME, (string)$value);
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
     * @param array|\Traversable $availableValues
     * @throws InvalidArgumentException
     */
    public function setAvailableValues($availableValues): void
    {
        if ($availableValues instanceof \Traversable) {
            $availableValues = iterator_to_array($availableValues);
        } elseif (!is_array($availableValues)) {
            throw new InvalidArgumentException("Argument 'values' must be array or Traversable.");
        }

        $values = array();
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
     * @return array
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
     * @param int $value
     * @return bool
     */
    protected function isValidValue(int $value): bool
    {
        return array_key_exists($value, $this->getAvailableValues()) ? TRUE : FALSE;
    }
}