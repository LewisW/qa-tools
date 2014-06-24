<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\aik099\QATools\PageObject\ElementLocator;


use Mockery as m;
use aik099\QATools\PageObject\ElementLocator\DefaultElementLocatorFactory;

class DefaultElementLocatorFactoryTest extends \PHPUnit_Framework_TestCase
{

	const PROPERTY_CLASS = '\\aik099\\QATools\\PageObject\\Property';

	/**
	 * Locator class.
	 *
	 * @var string
	 */
	protected $locatorClass = '\\aik099\\QATools\\PageObject\\ElementLocator\\WaitingElementLocator';

	public function testCreateLocator()
	{
		$annotation_manager = m::mock('\\mindplay\\annotations\\AnnotationManager');
		$search_context = m::mock('\\aik099\\QATools\\PageObject\\ISearchContext');
		$factory = new DefaultElementLocatorFactory($search_context, $annotation_manager);

		$property = m::mock(self::PROPERTY_CLASS);
		$property->shouldReceive('getAnnotationsFromPropertyOrClass')->with('@timeout')->once()->andReturn(array());

		$this->assertInstanceOf($this->locatorClass, $factory->createLocator($property));
	}

}
