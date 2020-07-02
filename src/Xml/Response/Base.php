<?php

declare(strict_types = 1);

namespace Assessment\Xml\Response;

use Assessment\Xml\Validator;
use SimpleXMLElement;
use Exception;

abstract class Base
{
    const SENDER = 'Assessment';

    protected string $type;
    protected SimpleXMLElement $xml;

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
        $this->type = $type;

        $xmlTag = sprintf('<?xml version="1.0" encoding="UTF-8"?><%s/>', $type);
        $this->xml = new SimpleXMLElement($xmlTag);
        $this->xml->addChild('header');
        $this->xml->addChild('body');

        $this->setHeader($recipient, $reference);
    }

    /**
     * All child of this class must implement this
     * @return $this
     */
    abstract public function setBody();

    /**
     * Populate the header node with the required (and optional) elements
     * @param string $recipient
     * @param string $reference
     */
    protected function setHeader(string $recipient, string $reference)
    {
        $header = $this->xml->header;
        $header->addChild('type', $this->type);
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

        $errorNode = $this->xml->body->addChild('error');
        $errorNode->addChild('code', (string) $code);
        if ($message) {
            $errorNode->addChild('message', htmlspecialchars($message, ENT_XML1));
        }
        $this->errSet = true;
        return $this;
    }

    /**
     * Output the whole xml as the http response
     * @throws Exception
     */
    public function output()
    {
        $xmlString = $this->xml->asXML();
        try {
            Validator::run($this->type, $xmlString);
        } catch (Exception $e) {
            throw new Exception(
                sprintf('Internal Server Error: output validation fails (%s)', $e->getCode()),
                500
            );
        }

        if (!headers_sent()) {
            header('Content-Type: application/xml; charset=utf-8');
        }
        echo $xmlString;
    }
}
