<?php

/**
 * Checks, if open_basedir is set.
 * 
 * @author Janos Pasztor <net@janoszen.hu>
 * @copyright 2011 János Pásztor All rights Reserved
 * @license https://github.com/janoszen/LAMPSecurityToolkit/wiki/License
 */
class OpenBasedirTest extends SecurityTest {
	/**
	 * Get the short name of the tests.
	 * 
	 * @return string
	 */
	function getName() {
		return 'Open Basedir';
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
		return 'Checks, if open_basedir is set.';
	}
	/**
	 * Returns the link to the details page of this issue.
	 * 
	 * @return string
	 */
	function getLink() {
		return 'https://github.com/janoszen/LAMPSecurityToolkit/wiki/Open-basedir-test';
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
			if (ini_get('open_basedir') == '') {
				$result->setCode(SecurityTestResult::WARNING);
				$result->setDescription('<p>The <a href="http://www.php.net/manual/en/ini.core.php#ini.open-basedir">open_basedir</a> ' .
						'option is not set. This may or may not be a problem, setting this option can help your ' . 
						'security. If you are using mod_php in a hosting environment, this is a must. To enable it, ' . 
						'set the following option in php.ini: <code>open_basedir = /your/web/root</code></p>');
			} else {
				$result->setCode(SecurityTestResult::OK);
			}
		}
        return $result;
	}
}