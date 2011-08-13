<?php

/**
 * Checks, if display_errors is set to on.
 * 
 * @author Janos Pasztor <net@janoszen.hu>
 * @copyright 2011 János Pásztor All rights Reserved
 * @license https://github.com/janoszen/LAMPSecurityToolkit/wiki/License
 */
class DisplayErrorsTest extends SecurityTest {
	/**
	 * Get the short name of the tests.
	 * 
	 * @return string
	 */
	function getName() {
		return 'Display Errors';
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
		return 'Checks, if display_errors is set to on.';
	}
	/**
	 * Returns the link to the details page of this issue.
	 * 
	 * @return string
	 */
	function getLink() {
		return 'https://github.com/janoszen/LAMPSecurityToolkit/wiki/Display-errors-test';
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
			if (ini_get('display_errors')) {
				$result->setCode(SecurityTestResult::CRITICAL);
				$result->setDescription('<p>The <a href="http://www.php.net/manual/en/errorfunc.configuration.php#ini.display-errors">display_errors</a> ' .
						'option is set. This allows PHP to write program errors to the browser, thereby revealing ' . 
						'security-related information. To disable it, set the following option in php.ini: ' . 
						'<code>display_errors = Off</code></p>');
			} else {
				$result->setCode(SecurityTestResult::OK);
			}
		}
        return $result;
	}
}