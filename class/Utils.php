<?php 
/**
 * 
 */
class Utils
{
	public static function jsonInside($jsonArray)
	{
		foreach ($jsonArray as $key1 => $value) {
			if (is_string($value)) {
				if (substr($value, 0, 1) == "[") {
					$value = json_decode($value);
					unset($jsonArray->$key1);
					foreach ($value as $i => $val) {
						$jsonArray->$key1[$i] = $val;
					}
				}
			}
		}
		return $jsonArray;
	}
}
?>