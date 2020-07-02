<?php

declare(strict_types = 1);

namespace Assessment;

use Assessment\Xml\Parser;
use Assessment\Xml\Response\Nack;
use Exception;

/**
 * Class Application
 */
class Application
{
    /**
     * Main method of the Application class
     */
    public static function run()
    {
        try {
            self::checkRequestMethod();
            $payload = self::getPayload();
            $xmlParser = new Parser($payload);
            $xmlParser->respond();
        } catch (Exception $e) {
            self::abort((int) $e->getCode(), $e->getMessage());
            exit;
        }
    }

    /**
     * Abort the application: output a NACK response
     * @param int $code The HTTP status code
     * @param string $text The error message
     * @throws Exception
     */
    private static function abort(int $code, string $text)
    {
        header($_SERVER['SERVER_PROTOCOL'] . " $code $text", true, $code);
        (new Nack())
            ->setError($code, $text)
            ->output();
    }

    /**
     * Only allow POST requests
     * @throws Exception
     */
    private static function checkRequestMethod()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception('Bad Request: Only HTTP POST request is supported', 400);
        }
    }

    /**
     * Retrieve the payload of the http post
     * @return string
     * @throws Exception
     */
    private static function getPayload(): string
    {
        $payload = file_get_contents('php://input');
        if (empty($payload)) {
            throw new Exception('Unprocessable Entity: empty payload', 422);
        }
        return $payload;
    }
}
