<?php
/**
 * This file is part of the qa-tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/qa-tools
 */

namespace aik099\QATools\HtmlElements\Exception;


/**
 * Exception related to Form.
 */
class FormException extends TypifiedElementException
{

	const TYPE_UNKNOWN_FIELD = 221;

	const TYPE_READONLY_FIELD = 222;
}
