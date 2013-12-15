<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\PageObject\Annotations;


use mindplay\annotations\Annotation;

/**
 * Defines Selenium-related metadata.
 *
 * @usage('class'=>true, 'inherited'=>true)
 */
class PageUrlAnnotation extends Annotation
{

	/**
	 * Url to a page.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Initialize the annotation.
	 *
	 * @param array $properties Annotation parameters.
	 *
	 * @return void
	 */
	public function initAnnotation(array $properties)
	{
		$this->map($properties, array('url'));

		parent::initAnnotation($properties);
	}

}
