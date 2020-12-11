<?php

/**
 * PHP XML processing example scripts
 *
 * PHP version 7
 *
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @author Kemal Djakman
 * @link
 *
 */

declare(strict_types=1);

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

    public function setBody(): self
    {
        return $this;
    }
}
