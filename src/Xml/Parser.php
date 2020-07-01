<?php

declare(strict_types = 1);

namespace Assessment\Xml;

use Assessment\Xml\Response\Ping;
use Assessment\Xml\Response\Reverse;
use Assessment\Xml\Validator;
use SimpleXMLElement;
use Exception;

class Parser
{
    /**
     * @var string
     */
    protected $payload;
    protected $type;

    /**
     * @var SimpleXMLElement
     */
    protected $xml;

    public function __construct(string $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @throws Exception
     */
    public function respond()
    {
        $this->xml = $this->getXml();
        $this->type = self::getRequestType($this->xml);
        Validator::run($this->type, $this->payload);

        $this->processRequest($this->type, $this->xml);
    }

    /**
     * Transform payload into xml object
     * @return SimpleXMLElement
     * @throws Exception
     */
    public function getXml(): SimpleXMLElement
    {
        // use error-suppression operator in case of xml parse error
        $xml = @simplexml_load_string($this->payload, "SimpleXMLElement", LIBXML_NOCDATA);
        if ($xml === false) {
            throw new Exception('Bad Request: xml cannot be parsed', 400);
        }
        return $xml;
    }

    /**
     * @param SimpleXMLElement $xml
     * @return string
     * @throws Exception
     */
    private static function getRequestType(SimpleXMLElement $xml): string
    {
        if (empty($xml->header->type)) {
            throw new Exception('Unprocessable Entity: no type in header', 422);

        }
        return (string) $xml->header->type;
    }

    /**
     * Transform request type into the appropriate class name, including the namespace
     * @param string $requestType
     * @return string
     */
    private static function getClassName(string $requestType): string
    {
        return  'Assessment\\Xml\\Response\\' . ucfirst(preg_replace('/_request$/', '', $requestType));
    }

    /**
     * Dynamically instantiate the appropriate class
     * @param string $requestType
     * @param SimpleXMLElement $xml
     */
    private static function processRequest(string $requestType, SimpleXMLElement $xml)
    {
        $className = self::getClassName($requestType);

        $object = new $className($xml);
        $object->setBody();
        $object->output();
    }
}
