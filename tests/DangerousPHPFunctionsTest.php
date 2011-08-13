<?php

/**
 * Checks, if potentially dangerous PHP functions are available.
 * 
 * @author Janos Pasztor <net@janoszen.hu>
 * @copyright 2011 János Pásztor All rights Reserved
 * @license https://github.com/janoszen/LAMPSecurityToolkit/wiki/License
 */
class DangerousPHPFunctionsTest extends SecurityTest {
	/**
	 * Get the short name of the tests.
	 * 
	 * @return string
	 */
	function getName() {
		return 'Dangerous PHP functions';
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
		return 'Checks, if potentially dangerous PHP functions are available';
	}
	/**
	 * Returns the link to the details page of this issue.
	 * 
	 * @return string
	 */
	function getLink() {
		return 'https://github.com/janoszen/LAMPSecurityToolkit/wiki/Dangerous-php-functions-test';
	}
	/**
	 * Run the test and return the result.
	 * 
	 * @param array $params
	 * @return SecurityTestResult
	 */
	function run($params = array()) {
		$result = &new SecurityTestResult();
		
		if (!is_callable('ini_get') || !is_callable('get_defined_functions')) {
			$result->setCode(SecurityTestResult::SKIPPED);
			$result->setDescription('ini_get() and get_defined_functions() is required to run this test.');
		} else {
			$functionlist = array(
				'exec', 'passthru', 'proc_open', 'shell_exec', 'system', 'pcntl_exec', 'posix_kill', 'posix_setsid',
				'pcntl_fork', 'posix_uname', 'dl', 'php_uname', 'phpinfo'
			);

			$afunctions = get_defined_functions();
			$dfunctions = explode(',', ini_get('disable_functions'));
			$rfunctions = array();
			foreach ($functionlist as $function) {
				if (in_array($function, $afunctions['internal']) &&
						!in_array($function, $dfunctions) &&
						is_callable($function)) {
					$rfunctions[] = '<a href="http://php.net/' . $function . '"><code>' . $function . '()</code></a>';
				}
			}
			if (!count($rfunctions)) {
				$result->setCode(SecurityTestResult::OK);
			} else {
				$result->setCode(SecurityTestResult::WARNING);
				$result->setDescription('Some functions, that may be dangerous are available for execution. ' .
						'Check, if they are needed and disable them, if applicable: ' . implode(', ', $rfunctions));
			}
		}
        return $result;
	}
}