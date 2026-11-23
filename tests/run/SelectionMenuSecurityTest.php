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

	public function testWhereUsedLinksRespectDestinationTokens(): void
	{
		$db = new mysqli(
			$_ENV['TEST_DB_HOSTNAME'],
			$_ENV['TEST_DB_USER'],
			$_ENV['TEST_DB_PASSWORD'],
			$_ENV['TEST_DB_SCHEMA'],
			$_ENV['TEST_DB_PORT']
		);
		list($Tokens, $Security) = $this->getUserSecurity($db);
		$Allowed = array();
		foreach (array('WhereUsedInquiry.php', 'SelectProduct.php', 'BOMInquiry.php', 'BOMs.php') as $Script) {
			$Allowed[$Script] = isset($Security[$Script]) && $Security[$Script] !== '' && in_array($Security[$Script], $Tokens);
		}
		if (!$Allowed['WhereUsedInquiry.php']) {
			$this->markTestSkipped('The test user cannot access the where-used inquiry');
		}

		$Crawler = $this->request('GET', self::$baseUri . '/WhereUsedInquiry.php');
		$BackLinks = $Crawler->filterXPath('//a[contains(concat(" ", normalize-space(@class), " "), " toplink ") and contains(@href, "/SelectProduct.php")]');
		$this->assertCount($Allowed['SelectProduct.php'] ? 1 : 0, $BackLinks);

		$MissingStockID = 'MISSING-' . strtoupper(bin2hex(random_bytes(8)));
		$Crawler = $this->request('GET', self::$baseUri . '/WhereUsedInquiry.php?StockID=' . urlencode($MissingStockID));
		if ($Allowed['SelectProduct.php']) {
			$this->assertStringContainsString('select from the Select Item link above', $Crawler->text());
		} else {
			$this->assertStringContainsString('Back button to enter a valid item code', $Crawler->text());
			$this->assertStringNotContainsString('select from the Select Item link above', $Crawler->text());
		}

		$Statement = $db->prepare("SELECT bom.component, bom.parent
			FROM bom
			INNER JOIN stockmaster ON stockmaster.stockid = bom.parent
			INNER JOIN stockmaster AS component ON component.stockid = bom.component
			INNER JOIN locationusers ON locationusers.loccode = bom.loccode
			WHERE locationusers.userid = ?
				AND locationusers.canview = 1
				AND bom.effectiveafter <= CURRENT_DATE
				AND bom.effectiveto > CURRENT_DATE
			LIMIT 1");
		$Statement->bind_param('s', $_ENV['TEST_USER_ACCOUNT']);
		$Statement->execute();
		$Fixture = $Statement->get_result()->fetch_assoc();
		if ($Fixture === null) {
			$this->markTestSkipped('No accessible where-used fixture is available');
		}
		$Crawler = $this->request('GET', self::$baseUri . '/WhereUsedInquiry.php?StockID=' . urlencode($Fixture['component']));
		$Rows = $Crawler->filter('table.selection tbody tr');
		$this->assertGreaterThan(0, $Rows->count());
		foreach ($Rows as $Row) {
			$Cells = (new Symfony\Component\DomCrawler\Crawler($Row))->filter('td');
			$this->assertNotSame('', trim($Cells->eq(0)->text()));
			$this->assertSame($Allowed['BOMInquiry.php'], $Cells->eq(0)->filter('a')->count() > 0);
			$this->assertSame($Allowed['BOMs.php'], $Cells->eq(7)->filter('a')->count() > 0);
			$this->assertSame($Allowed['SelectProduct.php'], $Cells->eq(8)->filter('a')->count() > 0);
		}
	}

	private function getUserSecurity(mysqli $db): array
	{
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
		return array($tokens, $security);
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

		list($tokens, $security) = $this->getUserSecurity($db);

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
