<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2017 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 *
 * #######################################
 * #     e107 masthead plugin    		 #
 * #     by Jimako                       #
 * #     https://www.e107sk.com          #
 * #######################################
 */

if (!defined('e107_INIT'))
{
	exit;
}

class masthead_shortcodes extends e_shortcode
{
	/* {MASTHEAD: mode=solid&template=solid} */
	public function sc_masthead($parm = '')
	{
		$mode = $parm['mode'];
		$template_key = $parm['template'];

		if (empty($mode))
		{
			return '';
		}

		if (empty($template_key))
		{
			$template_key = $mode;
		}

		if (empty($template_key))
		{
			return '';
		}

		$where = "element_mode LIKE '" . $mode . "'  AND element_visibility IN (" . USERCLASS_LIST . ")  LIMIT 1";

		$settings = e107::getDb()->retrieve('masthead', '*', $where);

		$values = e107::unserialize($settings['element_options']);

		if (empty($values))
		{
			return '';
		}

		$template = e107::getTemplate('masthead', 'masthead', $template_key);

		if (empty($template))
		{
			return '';
		}

		$start = '';
		$end = '';
		$text = '';
		$fieldvar = array();
		$var = array();

		/* only if element uses single fields */
		if (array_key_exists('fields', $values))
		{
			foreach ($values['fields'] as $key => $field)
			{
				if (!empty($field))
				{
					$key = strtoupper($key);
					$field = e107::getParser()->replaceConstants($field, 'full');
					$field = e107::getParser()->toHTML($field, true);
					$fieldsvar[$key] = $field;
				}
			}
		}

		$element = e107::getParser()->simpleParse($template['element'], $fieldsvar);

		$element = e107::getParser()->parseTemplate($element);

		return $element;
	}

}
