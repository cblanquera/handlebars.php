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
class Handler_Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var Handler
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Handler;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers HandlebarsPHP\Handler::compile
     */
    public function testCompile()
    {
        $template = $this->object->compile('{{foo}}{{{foo}}}');

        $results = $template(['foo' => '<strong>foo</strong>']);

        $this->assertEquals('&lt;strong&gt;foo&lt;/strong&gt;<strong>foo</strong>', $results);
    }

    /**
     * @covers HandlebarsPHP\Handler::getCache
     */
    public function testGetCache()
    {
        $this->assertNull($this->object->getCache());
    }

    /**
     * @covers HandlebarsPHP\Handler::getHelper
     */
    public function testGetHelper()
    {
        $this->assertInstanceOf('Closure', $this->object->getHelper('if'));
        $this->assertNull($this->object->getHelper('foobar'));
    }

    /**
     * @covers HandlebarsPHP\Handler::getHelpers
     */
    public function testGetHelpers()
    {
        $helpers = $this->object->getHelpers();
        $this->assertTrue(is_array($helpers));
    }

    /**
     * @covers HandlebarsPHP\Handler::getPartial
     */
    public function testGetPartial()
    {
        $this->assertNull($this->object->getPartial('foobar'));
    }

    /**
     * @covers HandlebarsPHP\Handler::getPartials
     */
    public function testGetPartials()
    {
        $partials = $this->object->getPartials();
        $this->assertTrue(is_array($partials));
    }

    /**
     * @covers HandlebarsPHP\Handler::registerHelper
     */
    public function testRegisterHelper()
    {
        //simple helper
        $this->object->registerHelper('root', function() {
            return '/some/root';
        });

        $template = $this->object->compile('{{root}}/bower_components/eve-font-awesome/awesome.css');

        $results = $template();
        $this->assertEquals('/some/root/bower_components/eve-font-awesome/awesome.css', $results);

        $found = false;
        $self = $this;
        $template = $this
            ->object
            ->reset()
            ->registerHelper('foo', function(
                $bar,
                $four,
                $true,
                $null,
                $false,
                $zoo
            ) use ($self, &$found) {
                $self->assertEquals('', $bar);
                $self->assertEquals(4, $four);
                $self->assertTrue($true);
                $self->assertNull($null);
                $self->assertFalse($false);
                $self->assertEquals('foobar', $zoo);
                $found = true;
                return $four + 1;
            })
            ->compile('{{foo bar 4 true null false zoo}}');

        $results = $template(['zoo' => 'foobar']);
        $this->assertTrue($found);
        $this->assertEquals(5, $results);

        $found = false;
        $template = $this
            ->object
            ->reset()
            ->registerHelper('foo', function(
                $number,
                $something1,
                $number2,
                $something2
            ) use ($self, &$found) {
                $self->assertEquals(4.5, $number);
                $self->assertEquals(4, $number2);
                $self->assertEquals('some"thi " ng', $something1);
                $self->assertEquals("some'thi ' ng", $something2);
                $found = true;

                return $something1.$something2;
            })
            ->compile('{{{foo 4.5 \'some"thi " ng\' 4 "some\'thi \' ng"}}}');

        $results = $template();

        $this->assertTrue($found);
        $this->assertEquals('some"thi " ng'."some'thi ' ng", $results);

        //attributes test
        $found = false;
        $template = $this
            ->object
            ->reset()
            ->registerHelper('foo', function(
                $bar,
                $number,
                $something1,
                $number2,
                $something2,
                $options
            ) use ($self, &$found) {
                $self->assertEquals(4.5, $number);
                $self->assertEquals(4, $number2);
                $self->assertEquals('some"thi " ng', $something1);
                $self->assertEquals("some'thi ' ng", $something2);
                $self->assertFalse($options['hash']['dog']);
                $self->assertEquals('meow', $options['hash']['cat']);
                $self->assertEquals('squeak squeak', $options['hash']['mouse']);

                $found = true;
                return $number2 + 1;
            })
            ->compile(
                '{{foo 4bar4 4.5 \'some"thi " ng\' 4 "some\'thi \' ng" '
                .'dog=false cat="meow" mouse=\'squeak squeak\'}}');

        $results = $template(['zoo' => 'foobar']);
        $this->assertTrue($found);
        $this->assertEquals(5, $results);
    }

    /**
     * @covers HandlebarsPHP\Handler::registerPartial
     */
    public function testRegisterPartial()
    {
        //basic
        $template = $this
            ->object
            ->reset()
            ->registerPartial('foo', 'This is {{ foo }}')
            ->registerPartial('bar', 'Foo is not {{ bar }}')
            ->compile('{{> foo }} ... {{> bar }}');

        $results = $template(['foo' => 'FOO', 'bar' => 'BAR']);

        $this->assertEquals('This is FOO ... Foo is not BAR', $results);

        //with scope
        $template = $this
            ->object
            ->reset()
            ->registerPartial('foo', 'This is {{ foo }}')
            ->registerPartial('bar', 'Foo is not {{ bar }}')
            ->compile('{{> foo }} ... {{> bar zoo}}');

        $results = $template(['foo' => 'FOO', 'bar' => 'BAR', 'zoo' => ['bar' => 'ZOO']]);

        $this->assertEquals('This is FOO ... Foo is not ZOO', $results);

        //with attributes
        $template = $this
            ->object
            ->reset()
            ->registerPartial('foo', 'This is {{ foo }}')
            ->registerPartial('bar', 'Foo is not {{ something }}')
            ->compile('{{> foo }} ... {{> bar zoo something="Amazing"}}');

        $results = $template(['foo' => 'FOO', 'bar' => 'BAR', 'zoo' => ['bar' => 'ZOO']]);

        $this->assertEquals('This is FOO ... Foo is not Amazing', $results);
    }

    /**
     * @covers HandlebarsPHP\Handler::reset
     */
    public function testReset()
    {
        //simple helper
        $helper = $this
            ->object
            ->registerHelper('root', function() {
                return '/some/root';
            })
            ->reset()
            ->getHelper('root');

        $this->assertNull($helper);

        $helper = $this
            ->object
            ->getHelper('if');

        $this->assertInstanceOf('Closure', $helper);
    }

    /**
     * @covers HandlebarsPHP\Handler::setCache
     */
    public function testSetCache()
    {
        $this->assertEquals('/foo/bar', $this->object->setCache('/foo/bar')->getCache());
    }

    /**
     * @covers HandlebarsPHP\Handler::setPrefix
     */
    public function testSetPrefix()
    {
        $instance = $this->object->setPrefix('foobar');
        $this->assertInstanceOf('HandlebarsPHP\Handler', $instance);
    }

    /**
     * @covers HandlebarsPHP\Handler::unregisterHelper
     */
    public function testUnregisterHelper()
    {
        $instance = $this->object->unregisterHelper('if');
        $this->assertInstanceOf('HandlebarsPHP\Handler', $instance);

        $this->assertNull($instance->getHelper('if'));
    }

    /**
     * @covers HandlebarsPHP\Handler::unregisterPartial
     */
    public function testUnregisterPartial()
    {
        $instance = $this->object
            ->registerPartial('foo', 'bar')
            ->unregisterPartial('foo');

        $this->assertInstanceOf('HandlebarsPHP\Handler', $instance);

        $this->assertNull($instance->getPartial('foo'));
    }
}
