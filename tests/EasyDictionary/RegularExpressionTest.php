<?php

declare(strict_types=1);

namespace EasyDictionary;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \EasyDictionary\RegularExpression
 */
class RegularExpressionTest extends TestCase
{
    /**
     * @covers \EasyDictionary\RegularExpression
     */
    public function testClassExistence()
    {
        self::assertTrue(class_exists('\EasyDictionary\RegularExpression'));
    }

    /**
     * @covers ::createSearchPattern
     */
    public function testCreateSearchPatternFunction()
    {
        self::assertEquals('/(one)/i', RegularExpression::createSearchPattern('one'));
        self::assertEquals('/(one)|(two)/i', RegularExpression::createSearchPattern('one,two'));
        self::assertEquals('/(one)|(two)/i', RegularExpression::createSearchPattern(['one', 'two']));
        self::assertEquals(
            '/((\s+)?one(\s+)?)/i',
            RegularExpression::createSearchPattern(['one'], true)
        );
    }
}
