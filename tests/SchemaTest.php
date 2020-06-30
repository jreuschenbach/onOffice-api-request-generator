<?php

use PHPUnit\Framework\TestCase;
use jr\ooapigenerator\JsonValidator;

class SchemaTest extends TestCase
{
    public function testReadAddress(): void
    {
        $schema = Opis\JsonSchema\Schema::fromJsonString(file_get_contents(__DIR__ . '/../resource/onOffice-api-json-schema.json'));
        $validator = new \Opis\JsonSchema\Validator();
        $validationResult = $validator->schemaValidation(json_decode(file_get_contents(__DIR__ . '/../resource/example-read-address.json')), $schema);
        $this->assertFalse($validationResult->hasErrors(), $this->extractErrorInfos($validationResult));
    }

    private function extractErrorInfos(\Opis\JsonSchema\ValidationResult $validationResult): string
    {
        $errorInfo = '';

        if ($validationResult->hasErrors()) {
            $validationError = $validationResult->getFirstError();
            $errorInfo = $validationError->keyword() . ': ';
            $keywordArgs = [];

            foreach ($validationError->keywordArgs() as $key => $value)
            {
                $keywordArgs [] = $key . ' = ' . $value;
            }

            $errorInfo .= implode(', ', $keywordArgs);
        }

        return $errorInfo;
    }
}