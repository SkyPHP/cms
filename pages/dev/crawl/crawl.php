<?php
$datafile = "/tmp/links.dat"; // file to keep the list of links in
$regex = "/<\s*a\s+[^>]*href\s*=\s*[\"']?([^\"' >]+)[\"' >]/isU";  // regex to search for hrefs

$handle = fopen($datafile, "r"); // open the data file
$buffer = fgets($handle, 4096);
$oldlinks[] = $buffer; // read the first link into an array
while (!feof($handle)) {
	$buffer = fgets($handle, 4096);
	array_push($oldlinks,$buffer); // read the rest of the links into an array
}
fclose($handle); // close the data file

foreach($oldlinks as $value) { // for every link in the array
	print $value; // print it out
	$remote = fopen(trim($value), "r") or die(); //open it or fail nicely
	$html = GetCurlPage($value);
	if (preg_match_all($regex, $html, $links)) { // if we find new links
		$local = fopen($datafile, "a+"); // open the data file
		foreach($links[1] as $value) { // for every new link
			$value.="\n"; // append a new line
			if(!in_array($value,$oldlinks)) { // if we haven't seen it before (nb - case sensitive)
				print($value); // print it out
				fwrite($local, $value); // and write it to file
			}
		}
		fclose($local); // close the data file
	}
	else {
		print("No links."); // we didn't find any links in the new file
	}
}
?>

