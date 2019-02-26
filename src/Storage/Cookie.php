<?php declare(strict_types=1);

namespace Surda\ItemsPerPage\Storage;

use Surda\ItemsPerPage\Exception\ValueNotFoundException;
use Nette\Http\Request;
use Nette\Http\Response;

class Cookie implements IStorage
{
    /** @var Request */
    private $httpRequest;

    /** @var Response */
    private $httpResponse;

    /**
     * @param Request  $httpRequest
     * @param Response $httpResponse
     */
    public function __construct(Request $httpRequest, Response $httpResponse)
    {
        $this->httpRequest = $httpRequest;
        $this->httpResponse = $httpResponse;
    }

    /**
     * @param string $key
     * @return string
     */
    public function read(string $key): string
    {
        $value = $this->httpRequest->getCookie($key);

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
        $this->httpResponse->setCookie($key, $value, '+365 days');
    }

    /**
     * @param string $key
     */
    public function delete(string $key): void
    {
        $this->httpResponse->deleteCookie($key);
    }
}