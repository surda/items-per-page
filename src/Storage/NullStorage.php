<?php declare(strict_types=1);

namespace Surda\ItemsPerPage\Storage;

use Surda\ItemsPerPage\Exception\ValueNotFoundException;

class NullStorage implements IStorage
{
    /** @var array */
    private $values = [];

    /**
     * @param  string $key
     * @return string
     * @throws ValueNotFoundException
     */
    public function read(string $key): string
    {
        if (array_key_exists($key, $this->values)) {
            $value = $this->values[$key];
        } else {
            $value = NULL;
        }

        if ($value === NULL) {
            throw new ValueNotFoundException();
        }

        return (string)$value;
    }

    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function write(string $key, string $value): void
    {
        $this->values[$key] = $value;
    }

    /**
     * @param  string $key
     * @return void
     */
    public function delete(string $key): void
    {
        if (array_key_exists($key, $this->values)) {
            unset($this->values[$key]);
        }
    }
}