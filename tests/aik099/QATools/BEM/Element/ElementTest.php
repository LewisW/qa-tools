<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\BEM\Element;


use aik099\QATools\BEM\Element\Element;
use aik099\QATools\PageObject\Element\WebElement;
use Mockery as m;
use Mockery\MockInterface;

class ElementTest extends PartTestCase
{

	/**
	 * WebElement.
	 *
	 * @var WebElement|MockInterface
	 */
	private $_webElement;

	/**
	 * Prepares mocks for object creation.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->partClass = '\\aik099\\QATools\\BEM\\Element\\Element';
		$this->_webElement = m::mock('\\aik099\\QATools\\PageObject\\Element\\WebElement');
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testConstructor()
	{
		$element = $this->createPart();

		$this->assertEquals('element-name', $element->getName());
		$this->assertSame($this->_webElement, $element->getWrappedElement());
	}

	/**
	 * Creates part to be tested.
	 *
	 * @return Element
	 */
	protected function createPart()
	{
		return new $this->partClass('element-name', $this->_webElement);
	}

}
