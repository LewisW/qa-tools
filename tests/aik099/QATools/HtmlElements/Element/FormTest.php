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


use aik099\QATools\HtmlElements\Element\Form;
use Mockery as m;

class FormTest extends ElementContainerTest
{

	const TYPIFIED_ELEMENT_CLASS = '\\aik099\\QATools\\HtmlElements\\Element\\TypifiedElement';

	/**
	 * Prepares test.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		if ( is_null($this->elementClass) ) {
			$this->elementClass = '\\aik099\\QATools\\HtmlElements\\Element\\Form';
		}

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testFill()
	{
		/** @var Form $form */
		$form = $this->mockElement(array('typify', 'getNodeElement', 'setValue'));

		$node_element1 = $this->createNodeElement();
		$typified_element1 = m::mock(self::TYPIFIED_ELEMENT_CLASS);
		$form->shouldReceive('getNodeElement')->with('f1')->once()->andReturn($node_element1);
		$form->shouldReceive('typify')->with($node_element1)->once()->andReturn($typified_element1);
		$form->shouldReceive('setValue')->with($typified_element1, 'v1')->once()->andReturn($form);

		$node_element2 = $this->createNodeElement();
		$typified_element2 = m::mock(self::TYPIFIED_ELEMENT_CLASS);
		$form->shouldReceive('getNodeElement')->with('f2')->once()->andReturn($node_element2);
		$form->shouldReceive('typify')->with($node_element2)->once()->andReturn($typified_element2);
		$form->shouldReceive('setValue')->with($typified_element2, 'v2')->once()->andReturn($form);

		$form_data = array('f1' => 'v1', 'f2' => 'v2');
		$this->assertSame($form, $form->fill($form_data));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\HtmlElements\Exception\FormException
	 * @expectedExceptionCode \aik099\QATools\HtmlElements\Exception\FormException::TYPE_NOT_FOUND
	 * @expectedExceptionMessage Form field "field-name" not found
	 */
	public function testGetNodeElementFailure()
	{
		if ( method_exists($this->selectorsHandler, 'xpathLiteral') ) {
			$this->selectorsHandler->shouldReceive('xpathLiteral')->with('field-name')->once()->andReturn("'field-name'");
			$this->webElement->shouldReceive('find')->with('named', array('field', "'field-name'"))->once()->andReturnNull();
		}
		else {
			$this->webElement->shouldReceive('find')->with('named', array('field', 'field-name'))->once()->andReturnNull();
		}

		$this->getElement()->getNodeElement('field-name');
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testGetNodeElementSuccess()
	{
		$node_element = $this->createNodeElement();

		if ( method_exists($this->selectorsHandler, 'xpathLiteral') ) {
			$this->selectorsHandler->shouldReceive('xpathLiteral')->with('field-name')->once()->andReturn("'field-name'");

			$this->webElement
				->shouldReceive('find')
				->with('named', array('field', "'field-name'"))
				->once()
				->andReturn($node_element);
		}
		else {
			$this->webElement
				->shouldReceive('find')
				->with('named', array('field', 'field-name'))
				->once()
				->andReturn($node_element);
		}

		$found_element = $this->getElement()->getNodeElement('field-name');

		$this->assertSame($node_element, $found_element);
		$this->assertEquals('XPATH', $found_element->getXpath());
	}

	/**
	 * Test description.
	 *
	 * @param string $tag_name      Tag name.
	 * @param string $input_type    Input type.
	 * @param string $element_class Element class.
	 *
	 * @return void
	 * @dataProvider typifyDataProvider
	 */
	public function testTypify($tag_name, $input_type, $element_class)
	{
		$node_element = $this->createNodeElement();
		$node_element->shouldReceive('getTagName')->withNoArgs()->once()->andReturn($tag_name);

		if ( $tag_name == 'input' ) {
			$node_element->shouldReceive('getAttribute')->with('type')->once()->andReturn($input_type);
		}

		$form_element = $this->getElement()->typify($node_element);
		$this->assertInstanceOf($element_class, $form_element);
	}

	/**
	 * Data provider for "typify" method testing.
	 *
	 * @return array
	 */
	public function typifyDataProvider()
	{
		return array(
			array('input', 'checkbox', '\\aik099\\QATools\\HtmlElements\\Element\\Checkbox'),
			array('input', 'radio', '\\aik099\\QATools\\HtmlElements\\Element\\RadioGroup'),
			array('input', 'file', '\\aik099\\QATools\\HtmlElements\\Element\\FileInput'),
			array('input', null, '\\aik099\\QATools\\HtmlElements\\Element\\TextInput'),
			array('input', 'text', '\\aik099\\QATools\\HtmlElements\\Element\\TextInput'),
			array('input', 'password', '\\aik099\\QATools\\HtmlElements\\Element\\TextInput'),
			array('input', 'color', '\\aik099\\QATools\\HtmlElements\\Element\\TextInput'),
			array('select', null, '\\aik099\\QATools\\HtmlElements\\Element\\Select'),
			array('textarea', null, '\\aik099\\QATools\\HtmlElements\\Element\\TextInput'),
		);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\HtmlElements\Exception\FormException
	 * @expectedExceptionCode \aik099\QATools\HtmlElements\Exception\FormException::TYPE_UNKNOWN_FIELD
	 * @expectedExceptionMessage Unable create typified element for element (class: aik099\QATools\PageObject\Element\WebElement; xpath: XPATH)
	 */
	public function testTypifyFailure()
	{
		$node_element = $this->createNodeElement();
		$node_element->shouldReceive('getTagName')->withNoArgs()->once()->andReturn('article');

		$this->getElement()->typify($node_element);
	}

	/**
	 * Test description.
	 *
	 * @return void
	 * @expectedException \aik099\QATools\HtmlElements\Exception\FormException
	 * @expectedExceptionCode \aik099\QATools\HtmlElements\Exception\FormException::TYPE_READONLY_FIELD
	 * @expectedExceptionMessage Element ELEMENT NAME doesn't support value changing
	 */
	public function testSetValueFailure()
	{
		$typified_element = m::mock(self::TYPIFIED_ELEMENT_CLASS);
		$typified_element->shouldReceive('__toString')->withNoArgs()->once()->andReturn('ELEMENT NAME');

		$this->getElement()->setValue($typified_element, 'the value');
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSetValueSuccess()
	{
		$mock_parts = array(
			self::TYPIFIED_ELEMENT_CLASS,
			'\\aik099\\QATools\\HtmlElements\\Element\\ISimpleSetter',
		);

		$typified_element = m::mock(implode(', ', $mock_parts));
		$typified_element->shouldReceive('setValue')->with('the value')->once()->andReturn($typified_element);

		$form = $this->getElement();
		$this->assertSame($form, $form->setValue($typified_element, 'the value'));
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testSubmit()
	{
		$this->webElement->shouldReceive('submit')->withNoArgs()->once()->andReturnNull();

		$form = $this->getElement();
		$this->assertSame($form, $form->submit());
	}

	/**
	 * Returns existing element.
	 *
	 * @return Form
	 */
	protected function getElement()
	{
		return $this->typifiedElement;
	}

}
