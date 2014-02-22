<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\BEM;


use aik099\QATools\PageObject\Page;

abstract class BEMPage extends Page
{

	/**
	 * Page Factory, used to create a Page.
	 *
	 * @var BEMPageFactory
	 */
	protected $pageFactory = null;

	/**
	 * Initialize the page.
	 *
	 * @param BEMPageFactory $page_factory Page factory.
	 */
	public function __construct(BEMPageFactory $page_factory)
	{
		parent::__construct($page_factory);

		$this->pageFactory = $page_factory;
	}

	/**
	 * Initializes BEM page elements.
	 *
	 * @return void
	 */
	public function initElements()
	{
		$this->pageFactory->initElements($this, $this->pageFactory->createDecorator($this));
	}

}
