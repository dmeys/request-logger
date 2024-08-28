<?php

namespace Dmeys\RequestLogger\Services;

class Concealer
{
	/**
	 * @param array $all_fields
	 * @param array $hide_fields
	 * @return array
	 */
	public function hide(array $all_fields, array $hide_fields = []): array
	{
		$result = [];
		foreach ($all_fields as $field => $value) {
			if (is_array($value)) {
				$result[$field] = $this->hide($value, $hide_fields);
			} else {
				if (!is_int($field) && in_array($field, $hide_fields)) {
					$result[$field] = config('request-logger.replacer_hidden_fields');
				} else {
					$result[$field] = $value;
				}
			}
		}

		return $result;
	}
}
