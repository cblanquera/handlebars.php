<?php //-->
/**
 * This file is part of the Handlebars PHP Template Engine.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace HandlebarsPHP;

use StdClass;
use PHPUnit_Framework_TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:11:00.
 */
class Compiler_Test extends PHPUnit_Framework_TestCase
{
     /**
     * @var Compiler
     */
    protected $object;

     /**
     * @var string
     */
    protected $source;

     /**
     * @var string
     */
    protected $template1;

     /**
     * @var string
     */
    protected $template2;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $handler = new Handler;
        $this->source = file_get_contents(__DIR__.'/assets/tokenizer.html');
        $this->object = new Compiler($handler, $this->source);

        $this->template1 = file_get_contents(__DIR__.'/assets/template1.php');
        $this->template2 = file_get_contents(__DIR__.'/assets/template2.php');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers HandlebarsPHP\Compiler::__construct
     */
    public function test__construct()
    {
        $handler = new Handler;
        $this->object->__construct($handler, $this->source);
    }

    /**
     * @covers HandlebarsPHP\Compiler::getSource
     */
    public function testGetSource()
    {
        $template = $this->object->getSource();
        $this->assertEquals($this->source, $template);
    }

    /**
     * @covers HandlebarsPHP\Compiler::compile
     * @covers HandlebarsPHP\Compiler::getTokenizeCallback
     * @covers HandlebarsPHP\Compiler::generateText
     * @covers HandlebarsPHP\Compiler::generateVariable
     * @covers HandlebarsPHP\Compiler::generateEscape
     * @covers HandlebarsPHP\Compiler::generateOpen
     * @covers HandlebarsPHP\Compiler::generateClose
     * @covers HandlebarsPHP\Compiler::generateHelpers
     * @covers HandlebarsPHP\Compiler::generatePartials
     * @covers HandlebarsPHP\Compiler::parseArguments
     * @covers HandlebarsPHP\Compiler::parseArgument
     * @covers HandlebarsPHP\Compiler::tokenize
     * @covers HandlebarsPHP\Compiler::prettyPrint
     * @covers HandlebarsPHP\Compiler::findSection
     * @covers HandlebarsPHP\Compiler::trim
     */
    public function testCompile()
    {
        $actual = $this->object->compile();
        $this->assertEquals($this->template1, $actual);

        $actual = $this->object->compile(false);
        $this->assertEquals($this->template2, $actual);
    }

    /**
     * @covers HandlebarsPHP\Compiler::setOffset
     */
    public function testSetOffset()
    {
        $actual = $this->object->setOffset(3);
        $this->assertInstanceOf('HandlebarsPHP\Compiler', $actual);
    }
}
