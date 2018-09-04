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
        $this->assertTrue(class_exists('\EasyDictionary\RegularExpression'));
    }

    /**
     * @covers ::createSearchPattern
     */
    public function testCreateSearchPatternFunction()
    {
        $this->assertEquals('/(one)/i', \EasyDictionary\RegularExpression::createSearchPattern('one'));
        $this->assertEquals('/(one)|(two)/i', \EasyDictionary\RegularExpression::createSearchPattern('one,two'));
        $this->assertEquals('/(one)|(two)/i', \EasyDictionary\RegularExpression::createSearchPattern(['one', 'two']));
        $this->assertEquals(
            '/((\s+)?one(\s+)?)/i',
            \EasyDictionary\RegularExpression::createSearchPattern(['one'], true)
        );
    }
}
