<?php declare(strict_types=1);

namespace Surda\ItemsPerPage\Storage;

use Nette\Http\SessionSection;
use Nette\Http\Session as NetteSession;
use Surda\ItemsPerPage\Exception\ValueNotFoundException;

class Session implements IStorage
{
    /** @var SessionSection */
    private $section;

    /**
     * @param string       $sectionName
     * @param NetteSession $session
     */
    public function __construct(string $sectionName, NetteSession $session)
    {
        $this->section = $session->getSection($sectionName);
    }

    /**
     * @param string $key
     * @return string
     */
    public function read(string $key): string
    {
        $value = $this->section[$key];

        if ($value === NULL) {
            throw new ValueNotFoundException();
        }

        return $value;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function write(string $key, string $value): void
    {
        $this->section[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function delete(string $key): void
    {
        unset($this->section[$key]);
    }


}