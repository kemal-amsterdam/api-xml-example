<?php

declare(strict_types = 1);

namespace Assessment\Xml\Response;

use SimpleXMLElement;
use Exception;

abstract class Base
{
    const SENDER = 'Assessment';

    protected SimpleXMLElement $xml;
    protected SimpleXMLElement $header;
    protected SimpleXMLElement $body;

    /**
     * @var bool Whether <error> node has already been applied under the <body> node
     */
    protected bool $errSet = false;

    /**
     * Base constructor.
     * @param string $type
     * @param string $recipient
     * @param string $reference
     */
    public function __construct(string $type, string $recipient = '', string $reference = '')
    {
        $xmlTag = '<?xml version="1.0" encoding="UTF-8"?>';
        $this->xml = new SimpleXMLElement($xmlTag . "<{$type}/>");
        $this->header = $this->xml->addChild('header');
        $this->body = $this->xml->addChild('body');

        $this->setHeader($type, $recipient, $reference);
    }

    /**
     * All child of this class must implement this
     * @return $this
     */
    abstract public function setBody();

    /**
     * Populate the header node with the required (and optional) elements
     * @param string $type
     * @param string $recipient
     * @param string $reference
     */
    protected function setHeader(string $type, string $recipient = '', string $reference = '')
    {
        $header = $this->header;
        $header->addChild('type', $type);
        $header->addChild('sender', self::SENDER);
        $header->addChild('recipient', htmlspecialchars($recipient, ENT_XML1));
        if ($reference) {
            $header->addChild('reference', htmlspecialchars($reference, ENT_XML1));
        }
        $header->addChild('timestamp', date('c'));
    }

    /**
     * Set the <error> node under the <body> node of the xml
     *
     * NOTE: The xsd specifies that the <error> node must be the last one in the <body> of the xml
     *
     * @param int $code
     * @param string $message
     * @return $this
     * @throws Exception
     */
    public function setError(int $code, string $message = ''): self
    {
        if ($this->errSet) {
            throw new Exception('Internal Server Error: error element has already been set', 500);
        }

        $errorNode = $this->body->addChild('error');
        $errorNode->addChild('code', (string) $code);
        if ($message) {
            $errorNode->addChild('message', htmlspecialchars($message, ENT_XML1));
        }
        $this->errSet = true;
        return $this;
    }

    /**
     * Output the whole xml as the http response
     */
    public function output()
    {
        if (!headers_sent()) {
            header('Content-Type: application/xml; charset=utf-8');
        }

        echo $this->xml->asXML();
    }
}
