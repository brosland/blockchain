<?php

namespace Brosland\Blockchain;

class Utils extends \Nette\Object
{

	/**
	 * @param array $required
	 * @param array $subject
	 */
	public static function checkRequiredFields(array $required, array $subject)
	{
		foreach ($required as $key => $value)
		{
			if (is_array($value))
			{
				if (!isset($subject[$key]))
				{
					throw new \Nette\InvalidArgumentException("Missing field '{$key}'.");
				}
				else if (!is_array($subject[$key]))
				{
					throw new \Nette\InvalidArgumentException("Field '{$key}' have to be an array.");
				}

				self::checkRequiredFields($required[$key], $subject[$key]);
			}
			else if (!array_key_exists($value, $subject))
			{
				throw new \Nette\InvalidArgumentException("Missing field '{$value}'.");
			}
		}
	}
}