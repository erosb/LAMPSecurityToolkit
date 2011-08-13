<?php

/**
 * Checks, if allow_url_include is enabled.
 * 
 * @author Janos Pasztor <net@janoszen.hu>
 * @copyright 2011 János Pásztor All rights Reserved
 * @license https://github.com/janoszen/LAMPSecurityToolkit/wiki/License
 */
class RemoteURLIncludeTest extends SecurityTest {
	/**
	 * Get the short name of the tests.
	 * 
	 * @return string
	 */
	function getName() {
		return 'Remote file inclusion';
	}
	/**
	 * Return the category name of the test
	 * @return string
	 */
	function getCategory() {
		return 'PHP';
	}
	/**
	 * Returns the detailed description of this test.
	 * 
	 * @return string
	 */
	function getDescription() {
		return 'Checks, if allow_url_include is enabled.';
	}
	/**
	 * Returns the link to the details page of this issue.
	 * 
	 * @return string
	 */
	function getLink() {
		return 'https://github.com/janoszen/LAMPSecurityToolkit/wiki/Remote-url-inclusion-test';
	}
	/**
	 * Run the test and return the result.
	 * 
	 * @param array $params
	 * @return SecurityTestResult
	 */
	function run($params = array()) {
		$result = &new SecurityTestResult();
		
		if (!is_callable('ini_get')) {
			$result->setCode(SecurityTestResult::SKIPPED);
			$result->setDescription('ini_get() is required to run this test.');
		} else {
			if (ini_get('allow_url_include') == '1') {
				$result->setCode(SecurityTestResult::WARNING);
				$result->setDescription('<p>Remote file inclusion is enabled! This can be used with poorly written ' . 
						'code to execute malicious code from remote servers. Unless you use some edge case software ' .
						'(like PHP/Java Bridge) disable including remote files by setting the following option in ' .
						'php.ini: <code>allow_url_include = off</code></p>');
			} else {
				$result->setCode(SecurityTestResult::OK);
			}
		}
        return $result;
	}
}