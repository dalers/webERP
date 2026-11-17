<?php

require_once(__DIR__ . '/../src/LoggedInUserTestCase.php');

class SelectionMenuSecurityTest extends LoggedInUserTestCase
{
	/**
	 * @dataProvider selectionPages
	 */
	public function testSelectionMenuUsesDestinationTokens($FixtureSQL, $Page, $Parameter, $Heading, $Scripts): void
	{
		$db = new mysqli(
			$_ENV['TEST_DB_HOSTNAME'],
			$_ENV['TEST_DB_USER'],
			$_ENV['TEST_DB_PASSWORD'],
			$_ENV['TEST_DB_SCHEMA'],
			$_ENV['TEST_DB_PORT']
		);
		$fixture = $db->query($FixtureSQL)->fetch_row();
		if ($fixture === null) {
			$this->markTestSkipped('No selection fixture is available');
		}

		$statement = $db->prepare('SELECT fullaccess FROM www_users WHERE userid = ?');
		$statement->bind_param('s', $_ENV['TEST_USER_ACCOUNT']);
		$statement->execute();
		$user = $statement->get_result()->fetch_assoc();
		$this->assertNotNull($user);
		$statement = $db->prepare('SELECT tokenid FROM securitygroups WHERE secroleid = ?');
		$statement->bind_param('s', $user['fullaccess']);
		$statement->execute();
		$tokens = array_column($statement->get_result()->fetch_all(MYSQLI_ASSOC), 'tokenid');
		$security = array_column($db->query('SELECT script, pagesecurity FROM scripts')->fetch_all(MYSQLI_ASSOC), 'pagesecurity', 'script');

		$crawler = $this->request('GET', self::$baseUri . '/' . $Page . '?' . $Parameter . '=' . urlencode($fixture[0]));
		$menu = $crawler->filterXPath('//table[contains(concat(" ", normalize-space(@class), " "), " selection ")][.//th[contains(., "' . $Heading . '")]]');
		$this->assertCount(1, $menu);
		$links = $menu->filter('a')->each(function ($node) {
			return basename(parse_url($node->attr('href'), PHP_URL_PATH));
		});
		foreach ($Scripts as $script) {
			$allowed = isset($security[$script]) && $security[$script] !== '' && in_array($security[$script], $tokens);
			$this->assertSame($allowed, in_array($script, $links), $Page . ': ' . $script);
		}
	}

	public static function selectionPages(): array
	{
		return array(
			array(
				'SELECT stockmaster.stockid FROM stockmaster INNER JOIN stockcategory ON stockmaster.categoryid = stockcategory.categoryid LIMIT 1',
				'SelectProduct.php', 'StockID', 'Item Inquiries',
				array('StockMovements.php', 'SelectSalesOrder.php', 'Stocks.php')
			),
			array(
				'SELECT supplierid FROM suppliers LIMIT 1',
				'SelectSupplier.php', 'SupplierID', 'Supplier Inquiries',
				array('SupplierInquiry.php', 'PO_Header.php', 'Suppliers.php')
			),
			array(
				'SELECT debtorno FROM custbranch LIMIT 1',
				'SelectCustomer.php', 'Select', 'Customer Inquiries',
				array('CustomerInquiry.php', 'SelectSalesOrder.php', 'Customers.php')
			)
		);
	}
}
