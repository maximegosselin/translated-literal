<?php
declare(strict_types = 1);

namespace MaximeGosselin\TranslatedLiteral\Test;

use MaximeGosselin\TranslatedLiteral\Literal;
use MaximeGosselin\TranslatedLiteral\LiteralInterface;
use PHPUnit_Framework_TestCase;

class LiteralTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorWithValidValues()
    {
        $literal = new Literal('fr-ca', 'Bonjour le monde');

        $this->assertInstanceOf(LiteralInterface::class, $literal);
        $this->assertEquals($literal->getLocale(), 'fr_CA');
        $this->assertEquals($literal->getDefault(), 'Bonjour le monde');
        $this->assertEquals($literal->translate('fr_ca'), 'Bonjour le monde');
    }
}
