<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\HtmlElements\Element;


use Mockery as m;
use aik099\QATools\HtmlElements\Element\TypifiedElement;
use aik099\QATools\PageObject\Element\WebElement;
use tests\aik099\QATools\TestCase;

class TypifiedElementTest extends TestCase
{

	const WEB_ELEMENT_CLASS = '\\aik099\\QATools\\PageObject\\Element\\WebElement';

	/**
	 * Element class.
	 *
	 * @var string
	 */
	protected $elementClass;

	/**
	 * Web Element.
	 *
	 * @var WebElement
	 */
	protected $webElement;

	/**
	 * Typified element.
	 *
	 * @var TypifiedElement
	 */
	protected $typifiedElement;

	/**
	 * Prepares mocks for object creation.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\tests\\aik099\\QATools\\HtmlElements\\Fixture\\Element\\TypifiedElementChild';
		}

		$this->webElement = m::mock(self::WEB_ELEMENT_CLASS);
		$this->webElement->shouldReceive('getSession')->withNoArgs()->andReturn($this->session);

		$this->setUpBeforeCreateElement();

		$this->typifiedElement = $this->createElement();
	}

	/**
	 * Occurs before element creation in setUp.
	 *
	 * @return void
	 */
	protected function setUpBeforeCreateElement()
	{

	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testConstructor()
	{
		$this->assertSame($this->webElement, $this->typifiedElement->getWrappedElement());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testFromNodeElement()
	{
		$node_element = $this->createNodeElement();

		/* @var $element_class TypifiedElement */
		$element_class = $this->elementClass;
		$element = $element_class::fromNodeElement($node_element, $this->pageFactory);

		$this->assertInstanceOf($element_class, $element);
		$this->assertInstanceOf(self::WEB_ELEMENT_CLASS, $element->getWrappedElement());
		$this->assertEquals($node_element->getXpath(), $element->getXpath());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetName()
	{
		$expected = 'OK';
		$this->assertSame($this->typifiedElement, $this->typifiedElement->setName($expected));
		$this->assertEquals($expected, $this->typifiedElement->getName());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetSession()
	{
		$this->assertSame($this->session, $this->typifiedElement->getSession());
	}

	/**
	 * Test description.
	 *
	 * @param string $method_name Method name.
	 *
	 * @return void
	 * @dataProvider simpleMethodDataProvider
	 */
	public function testSimpleMethod($method_name)
	{
		$expected = 'C';
		$this->webElement->shouldReceive($method_name)->once()->andReturn($expected);

		$this->assertSame($expected, $this->typifiedElement->$method_name());
	}

	/**
	 * Provides sample data for simple methods test.
	 *
	 * @return array
	 */
	public function simpleMethodDataProvider()
	{
		return array(
			array('isVisible'),
			array('isValid'),
			array('getXpath'),
			array('getTagName'),
			array('getContainer'),
		);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testAttribute()
	{
		$expected = 'B';
		$this->webElement->shouldReceive('hasAttribute')->with('A')->once()->andReturn($expected);
		$this->webElement->shouldReceive('getAttribute')->with('A')->once()->andReturn($expected);

		$this->assertSame($expected, $this->typifiedElement->hasAttribute('A'));
		$this->assertSame($expected, $this->typifiedElement->getAttribute('A'));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetContainer()
	{
		$container = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');
		$this->webElement->shouldReceive('setContainer')->with($container)->once()->andReturnNull();

		$this->assertSame($this->typifiedElement, $this->typifiedElement->setContainer($container));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetContainer()
	{
		$expected = 'OK';
		$this->webElement->shouldReceive('getContainer')->once()->andReturn($expected);

		$this->assertEquals($expected, $this->typifiedElement->getContainer());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testToString()
	{
		$element = $this->createElement();
		$this->webElement->shouldReceive('getXpath')->andReturn('XPATH');

		$expected = 'element (class: ' . get_class($element) . '; xpath: XPATH)';
		$this->assertEquals($expected, (string)$element);
	}

	/**
	 * Create element.
	 *
	 * @return TypifiedElement
	 */
	protected function createElement()
	{
		return new $this->elementClass($this->webElement);
	}

	/**
	 * Mocks element.
	 *
	 * @param array $methods Methods to mock.
	 *
	 * @return TypifiedElement
	 */
	protected function mockElement(array $methods = array())
	{
		$method_string = $methods ? '[' . implode(',', $methods) . ']' : '';

		return m::mock($this->elementClass . $method_string, array($this->webElement, $this->pageFactory));
	}

}
