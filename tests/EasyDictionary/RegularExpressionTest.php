<?php

declare(strict_types=1);

namespace EasyDictionary;

use EasyDictionary\RegularExpression;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass RegularExpression
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
        $this->assertEquals('/(one)/i', RegularExpression::createSearchPattern('one'));
        $this->assertEquals('/(one)|(two)/i', RegularExpression::createSearchPattern('one,two'));
        $this->assertEquals('/(one)|(two)/i', RegularExpression::createSearchPattern(['one', 'two']));
        $this->assertEquals(
            '/((\s+)?one(\s+)?)/i',
            RegularExpression::createSearchPattern(['one'], true)
        );
    }
}
