<?php

declare(strict_types = 1);

namespace Assessment\Xml\Response;

use SimpleXMLElement;
use Exception;

/**
 * concrete class Ping
 */
class Ping extends Base
{
    private SimpleXMLElement $request;

    /**
     * Ping constructor.
     * @param SimpleXMLElement $requestXml
     */
    public function __construct(SimpleXMLElement $requestXml)
    {
        $this->request = $requestXml;

        $recipient = (string) $requestXml->header->sender ?? '';
        $reference = (string) $requestXml->header->reference ?? '';
        parent::__construct('ping_response', $recipient, $reference);
    }

    /**
     * Add the nodes under the body node
     * @return $this
     * @throws Exception
     */
    public function setBody()
    {
        if ($this->errSet) {
            throw new Exception('Internal Server Error: error element has already been set', 500);
        }

        $xml = $this->request;
        if (isset($xml->body->echo)) {
            $this->body->addChild('echo', htmlspecialchars((string) $xml->body->echo, ENT_XML1));
        }
        return $this;
    }
}
