<?php

/**
 * Checks, if the /etc/shadow or the /etc/shadow- file is readable by PHP.
 * 
 * @author Janos Pasztor <net@janoszen.hu>
 * @copyright 2011 János Pásztor All rights Reserved
 * @license https://github.com/janoszen/LAMPSecurityToolkit/wiki/License
 */
class ShadowPermissionTest extends SecurityTest {
	/**
	 * Get the short name of the tests.
	 * 
	 * @return string
	 */
	function getName() {
		return 'Shadow File Permissions';
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
		return 'Checks, if the /etc/shadow or the /etc/shadow- file is readable by PHP.';
	}
	/**
	 * Returns the link to the details page of this issue.
	 * 
	 * @return string
	 */
	function getLink() {
		return 'https://github.com/janoszen/LAMPSecurityToolkit/wiki/Shadow-file-permissions-test';
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
			$desc = '';
			if (file_exists('/etc/shadow') && is_readable('/etc/shadow')) {
				$desc .= '<p>Your /etc/shadow file is readable by PHP. This file stores the user ' . 
						'passwords and presents a risk, if readable by anyone!</p>';
			}
			if (file_exists('/etc/shadow-') && is_readable('/etc/shadow-')) {
				$desc .= '<p>Your /etc/shadow- file is readable by PHP. This file stores the user ' . 
						'passwords and presents a risk, if readable by anyone!</p>';
			}
			if ($desc) {
				$result->setCode(SecurityTestResult::CRITICAL);
				$result->setDescription($desc);
			} else {
				$result->setCode(SecurityTestResult::OK);
			}
		}
        return $result;
	}
}
