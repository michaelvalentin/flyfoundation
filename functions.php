<?php

/**
 * Check if two arrays have exactly the same values
 * 
 * @param array $array1
 * @param array $array2
 * @return boolean
 */
function array_equals(array $array1,array $array2){
	return !array_diff($array1,$array2) && !array_diff($array2,$array1);
}