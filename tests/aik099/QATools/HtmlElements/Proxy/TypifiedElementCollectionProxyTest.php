<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace tests\aik099\QATools\HtmlElements\Proxy;


use aik099\QATools\HtmlElements\Proxy\TypifiedElementProxy;
use Mockery as m;

class TypifiedElementCollectionProxyTest extends TypifiedElementProxyTest
{

	const ELEMENT_CLASS = '\\tests\\aik099\\QATools\\HtmlElements\\Fixture\\Element\\TypifiedElementCollectionChild';

	public function testDefaultClassName()
	{
		$this->assertInstanceOf(self::ELEMENT_CLASS, $this->element->getObject());
	}

	public function testMethodForwardingSuccess()
	{
		$this->assertEquals(1, $this->element->proxyMe());
	}

	/**
	 * Creates a proxy.
	 *
	 * @return TypifiedElementProxy
	 */
	protected function createElement()
	{
		/** @var TypifiedElementProxy $proxy */
		$proxy = new $this->collectionClass($this->locator, $this->pageFactory, 'sample-name');
		$proxy->setClassName(self::ELEMENT_CLASS);

		return $proxy;
	}

}
