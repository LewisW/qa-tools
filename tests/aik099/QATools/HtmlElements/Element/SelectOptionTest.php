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


use aik099\QATools\HtmlElements\Element\Select;
use aik099\QATools\HtmlElements\Element\SelectOption;
use Mockery as m;

class SelectOptionTest extends AbstractTypifiedElementTest
{

	const SELENIUM_DRIVER_CLASS = '\\Behat\\Mink\\Driver\\Selenium2Driver';

	/**
	 * Select.
	 *
	 * @var Select
	 */
	protected $select;

	/**
	 * Prepares test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\SelectOption';
		}

		$this->select = m::mock('\\aik099\\QATools\\HtmlElements\\Element\\Select');

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @param boolean $checked Checked.
	 *
	 * @return void
	 * @dataProvider selectDeselectDataProvider
	 */
	public function testSelect($checked)
	{
		$this->webElement->shouldReceive('isSelected')->once()->andReturn($checked);
		$this->webElement->shouldReceive('getAttribute')->with('value')->andReturn('OK');

		$web_element = m::mock(self::WEB_ELEMENT_CLASS);
		$web_element->shouldReceive('selectOption')->with('OK', true)->times($checked ? 0 : 1)->andReturnNull();

		$this->select->shouldReceive('getWrappedElement')->andReturn($web_element);

		$element = $this->getElement();

		$this->assertSame($element, $element->select(true));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\HtmlElements\Exception\SelectException
	 * @expectedExceptionCode \aik099\QATools\HtmlElements\Exception\SelectException::TYPE_UNBOUND_OPTION
	 * @expectedExceptionMessage No SELECT element association defined
	 */
	public function testSelectException()
	{
		$this->webElement->shouldReceive('isSelected')->once()->andReturn(false);

		/** @var SelectOption $option */
		$option = new $this->elementClass($this->webElement);
		$option->select();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetSelect()
	{
		/** @var SelectOption $option */
		$option = new $this->elementClass($this->webElement);

		$this->assertSame($option, $option->setSelect($this->select));
	}

	/**
	 * Test description.
	 *
	 * @param boolean $checked      Checked.
	 * @param string  $driver_class Driver class.
	 *
	 * @return void
	 * @dataProvider selectDeselectDataProvider
	 */
	public function testDeselect($checked, $driver_class)
	{
		$this->webElement->shouldReceive('isSelected')->once()->andReturn(!$checked);

		if ( $driver_class == self::SELENIUM_DRIVER_CLASS ) {
			$this->webElement->shouldReceive('click')->times($checked ? 0 : 1)->andReturnNull();
		}
		else {
			if ( !$checked ) {
				$this->setExpectedException('\\aik099\\QATools\\HtmlElements\\Exception\\TypifiedElementException');
			}

			$this->webElement->shouldReceive('click')->never()->andReturnNull();
		}

		$driver = m::mock($driver_class);
		$this->session->shouldReceive('getDriver')->andReturn($driver);

		$element = $this->getElement();

		$this->assertSame($element, $element->deselect());
	}

	/**
	 * Data provider for "select/deselect" method test.
	 *
	 * @return array
	 */
	public function selectDeselectDataProvider()
	{
		return array(
			array(true, self::SELENIUM_DRIVER_CLASS),
			array(false, self::SELENIUM_DRIVER_CLASS),
			array(true, '\\Behat\\Mink\\Driver\\DriverInterface'),
			array(false, '\\Behat\\Mink\\Driver\\DriverInterface'),
		);
	}

	/**
	 * Test description.
	 *
	 * @param string  $test_method Typified element method.
	 * @param boolean $checked     Checked.
	 *
	 * @return void
	 * @dataProvider selectDataProvider
	 */
	public function testToggle($test_method, $checked)
	{
		/* @var $element SelectOption */
		$element = $this->mockElement(array($test_method));
		$element->shouldReceive($test_method)->once()->andReturn('OK');

		$this->assertEquals('OK', $element->toggle($checked));
	}

	/**
	 * Test description.
	 *
	 * @param string  $test_method Typified element method.
	 * @param boolean $checked     Checked.
	 *
	 * @return void
	 * @dataProvider selectDataProvider
	 */
	public function testToggleInvert($test_method, $checked)
	{
		/* @var $element SelectOption */
		$element = $this->mockElement(array($test_method, 'isSelected'));
		$element->shouldReceive('isSelected')->once()->andReturn(!$checked);
		$element->shouldReceive($test_method)->once()->andReturn('OK');

		$this->assertEquals('OK', $element->toggle());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testIsSelected()
	{
		$this->webElement->shouldReceive('isSelected')->once()->andReturn('OK');

		$this->assertEquals('OK', $this->getElement()->isSelected());
	}

	/**
	 * Data provider for "toggle" method test.
	 *
	 * @return array
	 */
	public function selectDataProvider()
	{
		return array(
			array('select', true),
			array('deselect', false),
		);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetValue()
	{
		$expected = 'OK';
		$this->webElement->shouldReceive('getAttribute')->with('value')->once()->andReturn($expected);

		$this->assertEquals($expected, $this->getElement()->getValue());
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetText()
	{
		$expected = 'OK';
		$this->webElement->shouldReceive('getText')->once()->andReturn($expected);

		$this->assertEquals($expected, $this->getElement()->getText());
	}

	/**
	 * Returns existing element.
	 *
	 * @return SelectOption
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

	/**
	 * Create element.
	 *
	 * @return SelectOption
	 */
	protected function createElement()
	{
		/** @var SelectOption $option */
		$option = new $this->elementClass($this->webElement);
		$option->setSelect($this->select);

		return $option;
	}

	/**
	 * Mocks element.
	 *
	 * @param array $methods Methods to mock.
	 *
	 * @return SelectOption
	 */
	protected function mockElement(array $methods = array())
	{
		$method_string = $methods ? '[' . implode(',', $methods) . ']' : '';

		return m::mock($this->elementClass . $method_string, array($this->webElement, $this->select));
	}

}
