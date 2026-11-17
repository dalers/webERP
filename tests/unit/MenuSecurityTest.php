<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 2) . '/includes/MiscFunctions.php';

class MenuSecurityTest extends TestCase
{
	public function testMenuItemRequiresItsOwnToken(): void
	{
		$security = array('Allowed.php' => '2', 'Public.php' => '0', 'Denied.php' => '8');
		$tokens = array('0', '2');

		$this->assertTrue(MenuItemIsAllowed('/Allowed.php?id=1', $security, $tokens));
		$this->assertTrue(MenuItemIsAllowed('/reports/Public.php', $security, $tokens));
		$this->assertFalse(MenuItemIsAllowed('/Denied.php', $security, $tokens));
		$this->assertFalse(MenuItemIsAllowed('/Missing.php', $security, $tokens));
	}

	public function testOnlyModulesWithAccessibleRenderedItemsAreVisible(): void
	{
		$modules = array('empty', 'denied', 'transactions', 'reports', 'maintenance', 'other');
		$items = array(
			'denied' => array('Reports' => array('URL' => array('/Denied.php', '/Missing.php'))),
			'transactions' => array('Transactions' => array('URL' => array('/Allowed.php?new=1'))),
			'reports' => array('Reports' => array('URL' => array('/Allowed.php'))),
			'maintenance' => array('Maintenance' => array('URL' => array('/Allowed.php'))),
			'other' => array('Other' => array('URL' => array('/Allowed.php')))
		);
		$security = array('Allowed.php' => '2', 'Denied.php' => '8');

		$this->assertSame(
			array('transactions', 'reports', 'maintenance'),
			GetVisibleMenuModules($modules, $items, $security, array('2'))
		);
		$this->assertSame(array(), GetVisibleMenuModules($modules, $items, $security, array()));
	}
}
