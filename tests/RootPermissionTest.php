<?php

/**
 * Checks, if the /root directory is readable by PHP.
 * 
 * @author Janos Pasztor <net@janoszen.hu>
 * @copyright 2011 János Pásztor All rights Reserved
 * @license https://github.com/janoszen/LAMPSecurityToolkit/wiki/License
 */
class RootPermissionTest extends SecurityTest {
	/**
	 * Get the short name of the tests.
	 * 
	 * @return string
	 */
	function getName() {
		return 'Root Directory Permissions';
	}
	/**
	 * Return the category name of the test
	 * @return string
	 */
	function getCategory() {
		return 'Filesystem';
	}
	/**
	 * Returns the detailed description of this test.
	 * 
	 * @return string
	 */
	function getDescription() {
		return 'Checks, if the /root directory is readable by PHP.';
	}
	/**
	 * Returns the link to the details page of this issue.
	 * 
	 * @return string
	 */
	function getLink() {
		return 'https://github.com/janoszen/LAMPSecurityToolkit/wiki/Root-directory-permissions-test';
	}
	/**
	 * Run the test and return the result.
	 * 
	 * @param array $params
	 * @return SecurityTestResult
	 */
	function run($params = array()) {
		$result = &new SecurityTestResult();
		
		if (!is_callable('file_exists') || !is_callable('is_readable')) {
			$result->setCode(SecurityTestResult::SKIPPED);
			$result->setDescription('file_exists() && is_readable() is required to run this test.');
		} else {
			if (file_exists('/root') && is_readable('/root')) {
				$result->setCode(SecurityTestResult::CRITICAL);
				$result->setDescription('<p>Your /root directory is readable by PHP. This is almost certainly a bad ' . 
						'idea.</p>');
			} else {
				$result->setCode(SecurityTestResult::OK);
			}
		}
        return $result;
	}
}