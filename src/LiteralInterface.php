<?php
declare(strict_types = 1);

namespace MaximeGosselin\TranslatedLiteral;

use InvalidArgumentException;
use UnexpectedValueException;

interface LiteralInterface
{
    public function getDefault():string;

    public function getLocale():string;

    public function translate(string $locale):string;

    /**
     * @throws UnexpectedValueException if the locale is not valid.
     */
    public function withLocale(string $locale):LiteralInterface;

    /**
     * @throws UnexpectedValueException if the locale is not valid.
     */
    public function withTranslation(string $locale, string $translation):LiteralInterface;

    public function toJson(int $options = 0):string;

    /**
     * @throws InvalidArgumentException if the JSON is not valid.
     */
    public static function fromJson(string $json):LiteralInterface;
}
