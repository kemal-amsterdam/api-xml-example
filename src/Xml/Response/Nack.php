<?php

declare(strict_types = 1);

namespace Assessment\Xml\Response;

/**
 * Concrete class Nack (Not ACKnowledged)
 */
class Nack extends Base
{
    public function __construct(string $recipient = '', string $reference = '')
    {
        parent::__construct('nack', $recipient, $reference);
    }

    public function setBody()
    {
        return $this;
    }
}
