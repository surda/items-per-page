<?php declare(strict_types=1);

namespace Surda\ItemsPerPage\Storage;

use Surda\ItemsPerPage\Exception\ValueNotFoundException;

interface IStorage
{
    /**
     * @param  string $key
     * @return string
     * @throws ValueNotFoundException
     */
    public function read(string $key): string;

    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function write(string $key, string $value): void;

    /**
     * @param  string $key
     * @return void
     */
    public function delete(string $key): void;

}