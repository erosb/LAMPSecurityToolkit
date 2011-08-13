<?php

/**
 * This is the abstract SecurityTest class all tests must implement.
 * 
 * @author Janos Pasztor <net@janoszen.hu>
 * @copyright 2011 János Pásztor All rights Reserved
 * @license https://github.com/janoszen/LAMPSecurityToolkit/wiki/License
 */
abstract class SecurityTest {
	/**
	 * Get the short name of the tests.
	 * 
	 * @return string
	 */
	abstract public function getName();
	
	/**
	 * Returns the detailed description of this test.
	 * 
	 * @return string
	 */
	abstract public function getDescription();
	
	/**
	 * Returns the link to the details page of this issue.
	 * 
	 * @return string
	 */
	abstract public function getLink();
	
	/**
	 * Run the test and return the result.
	 * 
	 * @return SecurityTestResult
	 */
	abstract public function run();
}
