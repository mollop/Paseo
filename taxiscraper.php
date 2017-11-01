<?php

header('Content-Type: text/html; charset=utf-8');
set_time_limit(1000000);

echo "<pre>";
//echo date('Y-m-d H:i:s');

//include master function list
include 'taxiscraper-master-function-list.php';
//include master array list (contains exceptions, DB columns, etc.)
include 'taxiscraper-master-array-list.php';

$i=0;

//xpath locations of each field we want to grab

foreach ($sites as $site => $skiptoggle) {

	foreach ($link_pages as $sitename => $siteurls) {
		if ($sitename == $site) {
			foreach ($siteurls as $siteurl) {
		
				connectUrl($siteurl);
//get all URLs and prepend
				$links = getUrl($link_pages_xpath[$site]['xpath'],$link_pages_xpath[$site]['prepend']);
//connect to each page URL
				print_r($links);

				foreach ($links as $number => $link) { 
					if ((($number + $skiptoggle) % 2 !== 0) || ($skiptoggle == 0))	{
						connectUrl($link); 
//fill variables

						$file_array[$i]['scrapeDate']  = date('Y-m-d H:i:s');
						$file_array[$i]['scrapeSource']  = $site;
						
						foreach ($header_xpaths as $sitename => $headersarray) {
							if ($sitename == $site) {
								//print_r($header_xpath);
								foreach ($headersarray as $header => $headerxpath) { 
									$file_array[$i][$header] = getHeaderData($headerxpath);
								} 
							}
						}			
					
//fill and format details array and merge with headers
						$details_array = formatDetailsData(
								getDetailsData($details_xpath[$site]),
								$site_delimiters[$site]
							);
						if (is_array($details_array)) {
								$file_array[$i] = array_merge($file_array[$i],$details_array);
						}
//trim non-numeric characters from each column in the $numeric_columns array (ID, price, etc.)
						foreach	($file_array[$i] as $field => &$detailkey) {
							if (in_array($field, $numeric_columns)) {
								$detailkey = trimNaN($detailkey);
							}
						}	
					}
					

					$i++;
					
//sleep randomly to disguise bot nature
					sleep (rand (3,6));
				
				}

			}
		}
	else {
		continue;
	}

	}
}
print_r($links);
print_r($file_array);


	//insert final array into db
if(!is_null($file_array)) {

	foreach ($file_array as $line) {
		mysqli_insert('tucarro',$line);
	}
}


//insert into excel

/*
$fp = fopen ('taxi_tucarro_arranged.csv','w+');

fpassthru ($fp);
	fputcsv($fp,$file_array,$delimiter = ",");


*/


