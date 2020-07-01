<?php

use PHPUnit\Framework\TestCase;
use jr\ooapigenerator\JsonValidator;

class SchemaTest extends TestCase
{
    public function testValid(): void
    {
        $schema = Opis\JsonSchema\Schema::fromJsonString(file_get_contents(__DIR__ . '/../resource/onOffice-api-json-schema.json'));
        $validator = new \Opis\JsonSchema\Validator();

        $pathValid = __DIR__.'/../resource/examples/valid/';
        $dirHandle = opendir($pathValid);

        while (false !== ($exampleFile = readdir($dirHandle)))
        {
            if (is_file($pathValid.$exampleFile))
            {
                $validationResult = $validator->schemaValidation(json_decode(file_get_contents($pathValid.$exampleFile)), $schema);
                $this->assertFalse($validationResult->hasErrors(), $exampleFile.': '.$this->extractErrorInfos($validationResult));
            }
        }
    }

    public function testInvalid(): void
    {
        $schema = Opis\JsonSchema\Schema::fromJsonString(file_get_contents(__DIR__ . '/../resource/onOffice-api-json-schema.json'));
        $validator = new \Opis\JsonSchema\Validator();

        $pathInvalid = __DIR__.'/../resource/examples/invalid/';
        $dirHandle = opendir($pathInvalid);

        while (false !== ($exampleFile = readdir($dirHandle)))
        {
            if (is_file($pathInvalid.$exampleFile))
            {
                $validationResult = $validator->schemaValidation(json_decode(file_get_contents($pathInvalid.$exampleFile)), $schema);
                $this->assertTrue($validationResult->hasErrors(), $exampleFile);
            }
        }
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