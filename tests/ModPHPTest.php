<?php

/**
 * Checks, if the Apache SAPI (mod_php) is being used.
 * 
 * @author Janos Pasztor <net@janoszen.hu>
 * @copyright 2011 János Pásztor All rights Reserved
 * @license https://github.com/janoszen/LAMPSecurityToolkit/wiki/License
 */
class ModPHPTest extends SecurityTest {
	/**
	 * Get the short name of the tests.
	 * 
	 * @return string
	 */
	function getName() {
		return 'mod_php';
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
		return 'Checks, the PHP interpreter runs using mod_php.';
	}
	/**
	 * Returns the link to the details page of this issue.
	 * 
	 * @return string
	 */
	function getLink() {
		return 'https://github.com/janoszen/LAMPSecurityToolkit/wiki/Mod-php-test';
	}
	/**
	 * Run the test and return the result.
	 * 
	 * @param array $params
	 * @return SecurityTestResult
	 */
	function run($params = array()) {
		$result = &new SecurityTestResult();
		
		if (!is_callable('php_sapi_name')) {
			$result->setCode(SecurityTestResult::SKIPPED);
			$result->setDescription('php_sapi_name() is required to run this test.');
		} else {
			if (stristr(php_sapi_name(), 'apache')) {
				$result->setCode(SecurityTestResult::WARNING);
				$result->setDescription('<p>You are using mod_php. This may under certain circumstances lead to ' . 
						'security problems. Hosting providers most notably should not use mod_php on their servers ' .
						'without extra protection.</p>');
			} else {
				$result->setCode(SecurityTestResult::OK);
			}
		}
        return $result;
	}
}