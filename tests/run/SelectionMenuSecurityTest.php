<?php

require_once(__DIR__ . '/../src/LoggedInUserTestCase.php');

class SelectionMenuSecurityTest extends LoggedInUserTestCase
{
	public function testProductLinkCanBeShared(): void
	{
		$StockID = $this->getProductCode();
		$Crawler = $this->request('GET', self::$baseUri . '/SelectProduct.php');
		$FormID = $Crawler->filter('input[name="FormID"]')->first()->attr('value');
		$Crawler = $this->request('POST', self::$baseUri . '/SelectProduct.php', array(
			'FormID' => $FormID,
			'Select' => $StockID
		));
		$Link = $Crawler->filter('#ProductLink');
		$this->assertCount(1, $Link);
		$Href = $Link->attr('href');
		$this->assertSame('SelectProduct.php', basename(parse_url($Href, PHP_URL_PATH)));
		parse_str(parse_url($Href, PHP_URL_QUERY), $Parameters);
		$this->assertSame($StockID, $Parameters['StockID']);

		$Crawler = $this->request('GET', self::$baseUri . '/SelectProduct.php?' . parse_url($Href, PHP_URL_QUERY));
		$this->assertCount(1, $Crawler->filter('#ProductLink'));
		$this->assertSame($Href, $Crawler->filter('#ProductLink')->attr('href'));
	}

	public function testMissingProductClearsSelection(): void
	{
		$StockID = $this->getProductCode();
		$Crawler = $this->request('GET', self::$baseUri . '/SelectProduct.php?StockID=' . urlencode($StockID));
		$this->assertCount(1, $Crawler->filter('#ProductLink'));

		$MissingStockID = 'MISSING-' . strtoupper(bin2hex(random_bytes(8)));
		$Crawler = $this->request('GET', self::$baseUri . '/SelectProduct.php?StockID=' . urlencode($MissingStockID));
		$this->assertStringContainsString('The stock item was not found', $Crawler->text());
		$this->assertStringContainsString($MissingStockID, $Crawler->text());
		$this->assertCount(0, $Crawler->filter('#ProductLink'));

		$Crawler = $this->request('GET', self::$baseUri . '/SelectProduct.php');
		$this->assertCount(0, $Crawler->filter('#ProductLink'));
		$this->assertCount(1, $Crawler->filter('input[name="StockCode"]'));

		$Crawler = $this->request('GET', self::$baseUri . '/SelectProduct.php?StockID%5B%5D=invalid');
		$this->assertStringContainsString('Invalid stock code', $Crawler->text());
		$this->assertCount(0, $Crawler->filter('#ProductLink'));
	}

	private function getProductCode(): string
	{
		$db = new mysqli(
			$_ENV['TEST_DB_HOSTNAME'],
			$_ENV['TEST_DB_USER'],
			$_ENV['TEST_DB_PASSWORD'],
			$_ENV['TEST_DB_SCHEMA'],
			$_ENV['TEST_DB_PORT']
		);
		$Fixture = $db->query("SELECT stockmaster.stockid
			FROM stockmaster INNER JOIN stockcategory ON stockmaster.categoryid = stockcategory.categoryid
			WHERE stockmaster.stockid REGEXP '^[A-Z0-9 _/-]+$'
			LIMIT 1")->fetch_row();
		if ($Fixture === null) {
			$this->markTestSkipped('No selection fixture is available');
		}
		return $Fixture[0];
	}

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
