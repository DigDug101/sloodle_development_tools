<?php
$v = isset($_REQUEST['v']) ? intval($_REQUEST['v']) : 0;

$ext_regex = ( isset($_REQUEST['format']) && ($_REQUEST['format'] == 'zip') ) ? '\.zip' : '\.tar\.gz';
$ext_regex = ( isset($_REQUEST['format']) && ($_REQUEST['format'] == 'iar') ) ? '\.iar' : $ext_regex;

$filename = 'sloodle';
if ($ext_regex == '\.iar') {
    $filename = 'sloodle_rezzer';
}

$fh = opendir('.');

$highest_major = 0;
$highest_minor = 0;
$highest_point = 0;
$highest_filename = '';

while (false !== ($entry = readdir($fh))) {
	if (preg_match('/^'.$filename.'_v(\d+)\.(\d+)\.(\d+).*?'.$ext_regex.'$/', $entry, $matches) ) {
		$major = $matches[1];
		$minor = $matches[2];
		$point = $matches[3];
		$highest = false;

		if ( ($v > 0) && ($major != $v) ) {
			continue;	
		}

		if ($major > $highest_major) {
			$highest = true;	
		} else if ( ($major == $highest_major) && ($minor > $highest_minor) ) {
			$highest = true;	
		} else if ( ($major == $highest_major) && ($minor == $highest_minor) && ($point > $highest_point) ) {
			$highest = true;	
		}
		if ($highest) {
			$highest_major = $major;
			$highest_minor = $minor;
			$highest_point = $point;
			$highest_filename = $entry;
		}
	}
}

if ($highest_filename != '') {
	header('Location: '.$highest_filename);
	exit;
}
header('HTTP/1.0 404 Not Found'); 
print "File not found.";
exit;

?>
