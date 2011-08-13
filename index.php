<?php

function fatal($error) {
	@header('HTTP/1.1 500 Internal Server Error');
	@header('Content-Type: text/plain');
	echo('The test run resulted in a fatal error. The error was: ' . $error);
	exit;
}

function loaddir($dir) {
	if (!($dh = opendir($dir))) {
		fatal('Failed to open classes dir for reading: ' . $dir);
	} else {
		$isphp4 = preg_match('/^4\./', phpversion());
		while (($file = readdir($dh)) != false) {
			if (preg_match('/\.php$/', $file)) {
				if (is_file($dir . '/' . $file) && file_exists($dir . '/' . $file) &&
						(!$isphp4 || !stripos($file, 'PHP5'))) {
					if (!include_once($dir . '/' . $file)) {
						fatal('Failed to include ' . $dir . '/' . $file);
					}
				}
			}
		}
		closedir($dh);
	}
}

function init() {
	loaddir(dirname(__FILE__) . '/classes');
	loaddir(dirname(__FILE__) . '/tests');
}

function listtests() {
	$tests = array();
	foreach (get_declared_classes() as $class) {
		if (is_subclass_of($class, 'SecurityTest')) {
			$testdata = array();
			eval('$testclass = &new ' . $class . '();');
			$testdata['name'] = $testclass->getName();
			$testdata['category'] = $testclass->getCategory();
			$testdata['description'] = $testclass->getDescription();
			$testdata['link'] = $testclass->getLink();
			$tests[$class] = $testdata;
		}
	}
	$json = '';
	if (function_exists('json_encode')) {
		$json = json_encode($tests);
	} else {
		$json .= '{';
		$first = true;
		foreach ($tests as $test => $testdata) {
			if (!$first) {
				$json .= ',';
			} else {
				$first = false;
			}
			$json .= $test . ':{';
			$json .= 'name:"' . addslashes($testdata['name']) . '",';
			$json .= 'category:"' . addslashes($testdata['category']) . '",';
			$json .= 'description:"' . addslashes($testdata['description']) . '",';
			$json .= 'link:"' . addslashes($testdata['link']) . '"';
			$json .= '}';
		}
		$json .= '}';
	}
	echo($json);
}

function runtest($test, $params) {
	eval('$testclass = &new ' . $test . '();');
	$result = $testclass->run($params);
	echo($result->toJSON());
}

function skiptest($test, $description = '') {
	$result = &new SecurityTestResult();
	$result->setCode(SecurityTestResult::SKIPPED);
	$result->setDescription($description);
	echo($result->toJSON());
}

init();
if (array_key_exists('action', $_GET)) {
	switch ($_GET['action']) {
		case 'gettests':
			header('Content-Type: text/plain');
			listtests();
			break;
		case 'runtest':
			if (array_key_exists('test', $_GET) &&
					preg_match('/Test$/', $_GET['test']) &&
					class_exists($_GET['test'])) {
				header('Content-Type: text/plain');
				runtest($_GET['test'], $_POST);
			} else {
				skiptest($_GET['test']);
			}
			break;
	}
} else {
	renderform();
}

function renderform() {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>LAMP Security Toolkit</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
	</head>
	<body>
		<div id="container">
			<h1>LAMP Security Toolkit</h1>
			<p>This toolkit is intended for <strong>webserver owners</strong> to check their setups. It is not, I repeat
				it is NOT intended for people to check their providers setups! This tool tries to circumvent security
				barriers in the system, so <strong>if you use it on a computer you are not authorized to penetration
					test, you might end up in jail!</strong></p>
			<div id="testlist">
				<p>Please select the tests you wish to run!</p>
			</div>
			<div id="resultlist">
				
			</div>
		</div>
		<script type="text/javascript" src="javascript/httpclient.js"></script>
		<script type="text/javascript" src="javascript/tester.js"></script>
	</body>
</html>
<?php
}
