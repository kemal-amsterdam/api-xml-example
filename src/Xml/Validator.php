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

namespace Assessment\Xml;

use DomDocument;
use JetBrains\PhpStorm\Pure;
use LibXMLError;
use Exception;

/**
 * Class Validator
 *
 * Class to validate a string representing an xml with the appropriate xsd
 */
class Validator
{
    /**
     * path to the folder containing xsd files
     */
    const XSD_PATH = SRC_PATH . '/xsd/';

    /**
     * different levels of libxml errors
     */
    const ERR_LEVELS = [
        LIBXML_ERR_WARNING => 'Warning',
        LIBXML_ERR_ERROR => 'Error',
        LIBXML_ERR_FATAL => 'Fatal error',
    ];

    /**
     * Validate the given xml string against the xsd file
     * @param string $type e.g. 'ping_request', 'reverse_request', ...
     * @param string $entityBody
     * @throws Exception
     */
    public static function run(string $type, string $entityBody)
    {
        $xsdFile = self::XSD_PATH . $type . '.xsd';
        if (!(is_file($xsdFile) && is_readable($xsdFile))) {
            throw new Exception("Internal Server Error: xsd file not found", 500);
        }

        libxml_use_internal_errors(true);
        $xml= new DOMDocument('1.0', 'UTF-8');
        $xml->loadXML($entityBody, LIBXML_NOBLANKS);
        if (!$xml->schemaValidate($xsdFile)) {
            throw new Exception(self::getXmlErrors(), 400);
        }
        libxml_use_internal_errors(false);
    }

    /**
     * Collect all found errors and return it as a string
     * @return string
     */
    protected static function getXmlErrors(): string
    {
        $result = [];
        foreach (libxml_get_errors() as $error) {
            $result[] = self::libxmlError($error);
        }
        libxml_clear_errors(); // prevent memory consumption
        return implode(' * ', $result);
    }

    /**
     * Return an instance of LibXMLError as a string
     * @param LibXMLError $error
     * @return string
     */
    #[Pure]
    protected static function libxmlError(LibXMLError $error): string
    {
        return sprintf('%s %s: %s on line %d',
            self::ERR_LEVELS[$error->level],
            $error->code,
            trim($error->message),
            $error->line
        );
    }
}
