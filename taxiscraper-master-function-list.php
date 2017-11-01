<?php

header('Content-Type: text/html; charset=utf-8');


echo "<pre>";
//echo date('Y-m-d H:i:s');



//run link script
//include 'link-tucarro.php';

date_default_timezone_set('America/New_York');

//function to grab URLs from a given xpath, optional prependage allows top part of the 
function getUrl($links_xpath,$prependage = null) {
	global $xpath;
	$elements = $xpath->evaluate($links_xpath);
	if ($elements->length > 0) {
		foreach ($elements as $element) {
    		$link[] = $prependage.$element->getAttribute('href');
    	}
	}
	return $link;
}


//function to trim whitespace
function trimWS($n) {
	if(is_string($n)) {
		$n = preg_replace('/\s+/',' ', $n);
		$n = trim($n);
	}
	return $n;
}

//function to eliminate non-numeric characters

function trimNaN($n) {
		if(is_string($n)) {
			$n = preg_replace("/[^0-9]/", "", $n);
		}
	return $n;
}

//Function to connect to designated URL
function connectUrl($link) {
	$ch = curl_init ();
	$timeout = 5;

	curl_setopt($ch, CURLOPT_URL, $link);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$html = curl_exec($ch);
	curl_close($ch);
	  
	$dom = new DOMDocument ();
	$html = '<?xml encoding="utf-8" ?>'.$html;
	@$dom->loadHtml($html);
	global $xpath;
	$xpath = new DomXPath($dom);
}

//function to get header data (title, price, etc)
function getHeaderData($xpath_text) {
	global $xpath;
	$string = $xpath->evaluate("string(".$xpath_text.")");
//	$string = $xpath->evaluate($xpath_text);
	if(is_string($string)) {
		return trimws($string);
	}
	else {
		return null;
	}
}

//function to get details data 
function getDetailsData($xpath_text) {
	global $xpath;
	$elements = $xpath->evaluate($xpath_text);

	if ($elements->length > 0) {
	  foreach ($elements as $element) {
		   $details[] = trimws($element->nodeValue);
	  }
	}
	else {
		$details[] = null;
	}
	return $details;
}

//function to change yes or no fields to 0 or 1		
function spanishBooleanize(&$k) {
		if(is_string($k)) {
		switch ($k) {
		case (($k == 'Sí') || ($k == 'Usado') || ($k == 'S?')):
				$k = 1;
		case (($k == 'No') || ($k == 'Nuevo')):
			$k = 0;
		default:
		continue;
		}
		return $k;
	}
}

//correct exceptions to column format used in db by checking against exceptions array

function changeExceptions(&$v) {
	global $exceptions;
	if (array_key_exists($v, $exceptions)) {
		$v = $exceptions[$v];
		return $v;
	}
}

//check that all description fields exist as database columns; if so, insert into array
function matchDBcolumns (&$category) {
	global $db_columns;
	if (in_array($category, $db_columns)) {
		return $category;
	}
	else {
		return null;
	}
}

//function to explode arrays with multiple delimiters

/*function to split details array based on delimiter and
set category = key and values = values */
function formatDetailsData($arrays,$delimiters) {
	if((is_array($arrays)) && (!is_null($arrays))) {
		foreach ($arrays as $array) {
			if (!is_null($array)) {

	/*split description fields into category and text based on delimiter
	or array of delimiters*/
				if(is_array($delimiters)) {
					foreach ($delimiters as $delimiter) {
						if (strpos($array, $delimiter) !== FALSE) {
							list($category,$text) = explode($delimiter, $array); 
							$category = $delimiter;
						}
					}
				}		
				elseif (strpos($array, $delimiters) !== FALSE) {
					list($category,$text) = explode($delimiters, $array);
				}
				if (isset($text)) {
					$text = trimws($text);
					spanishBooleanize($text);
				}
				if (isset($category)) {
					changeExceptions($category);
			 		if(!is_null(matchDBcolumns($category))) {
			 			$description_arranged[$category] = $text;
					}					
				}
			}
			else {
			$description_arranged = null;	
			}	
		}
	}
	return $description_arranged;
}




//mysqli insert function
function mysqli_insert($table, $assoc) {
    $mysqli = mysqli_connect("127.0.0.1","taxi","taxi","taxi");
    mysqli_select_db($mysqli,'taxi');
    mysqli_set_charset($mysqli,'utf8');
    foreach ($assoc as $column => $value) {
        $cols[] = $column;
        $vals[] = mysqli_real_escape_string($mysqli, $value);
    }
    $colnames = "`".implode("`, `", $cols)."`";
    $colvals = "'".implode("', '", $vals)."'";
    $mysql = mysqli_query($mysqli, "INSERT INTO $table ($colnames) VALUES ($colvals)") or die('Database Connection Error ('.mysqli_errno($mysqli).') '.mysqli_error($mysqli). " on query: INSERT INTO $table ($colnames) VALUES ($colvals)");
    mysqli_close($mysqli);
    if ($mysql)
        return TRUE;
    else return FALSE;
}
