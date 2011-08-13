<?php

/**
 * This is the abstract SecurityTest class all tests must implement.
 * 
 * @author Janos Pasztor <net@janoszen.hu>
 * @copyright 2011 János Pásztor All rights Reserved
 * @license https://github.com/janoszen/LAMPSecurityToolkit/wiki/License
 */
class SecurityTest {
	/**
	 * Get the short name of the tests.
	 * 
	 * @return string
	 */
	function getName() {
		return '';
	}
	/**
	 * Return the category name of the test
	 * @return string
	 */
	function getCategory() {
		return '';
	}
	/**
	 * Returns the detailed description of this test.
	 * 
	 * @return string
	 */
	function getDescription() {
		return '';
	}
	/**
	 * Returns the link to the details page of this issue.
	 * 
	 * @return string
	 */
	function getLink() {
		return '';
	}
	/**
	 * Run the test and return the result.
	 * 
	 * @param array $params
	 * @return SecurityTestResult
	 */
	function run($params = array()) {
		$result = &new SecurityTestResult();
        return $result;
	}
}
