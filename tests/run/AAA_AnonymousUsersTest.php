<?php

require_once(__DIR__ . '/../src/AnonymousUserTestCase.php');

/**
 * All public methods starting with `test` are tests which will be executed by PHPUnit
 */
class AAA_AnonymousUsersTest extends AnonymousUserTestCase
{
	/**
	 * @dataProvider stockLinkPages
	 */
	public function testStockLinkLoginFormPreservesStockCode(string $Page): void
	{
		$StockID = 'PART/12 #?&';
		$Crawler = $this->request('GET', self::$baseUri . '/' . $Page . '?StockID=' . urlencode($StockID));
		$Action = $Crawler->filter('form[name="LogIn"]')->attr('action');
		$this->assertSame('index.php', basename(parse_url($Action, PHP_URL_PATH)));
		$this->assertSame($Page, $Crawler->filter('input[name="StockLinkPage"]')->attr('value'));
		$this->assertSame($StockID, $Crawler->filter('input[name="StockLinkID"]')->attr('value'));

		$Crawler = $this->request('GET', self::$baseUri . '/index.php?StockID=' . urlencode($StockID));
		$this->assertSame('index.php', basename($Crawler->filter('form[name="LogIn"]')->attr('action')));
		$this->assertCount(0, $Crawler->filter('input[name="StockLinkPage"]'));

		$Crawler = $this->request('GET', self::$baseUri . '/' . $Page . '?StockID%5B%5D=invalid');
		$this->assertSame('index.php', basename($Crawler->filter('form[name="LogIn"]')->attr('action')));
		$this->assertCount(0, $Crawler->filter('input[name="StockLinkPage"]'));

		$Crawler = $this->request('GET', self::$baseUri . '/' . $Page);
		$this->assertCount(0, $Crawler->filter('input[name="StockLinkPage"]'));

		$Crawler = $this->request('GET', self::$baseUri . '/StockStatus.php?StockID=' . urlencode($StockID));
		$this->assertCount(0, $Crawler->filter('input[name="StockLinkPage"]'));
	}

	/**
	 * @dataProvider stockLinkPages
	 */
	public function testStockLinkSurvivesLoginRetry(string $Page): void
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
		$StockID = $Fixture[0];
		$Crawler = $this->request('GET', self::$baseUri . '/' . $Page . '?StockID=' . urlencode($StockID));
		$Crawler = $this->submitForm('SubmitUser', array(
			'CompanyNameField' => $_ENV['TEST_DB_SCHEMA'],
			'UserNameEntryField' => 'NOUSER' . strtoupper(bin2hex(random_bytes(5))),
			'Password' => 'incorrect'
		));
		$this->assertStringContainsString('incorrect password', $Crawler->text());
		$this->assertSame($Page, $Crawler->filter('input[name="StockLinkPage"]')->attr('value'));
		$this->assertSame($StockID, $Crawler->filter('input[name="StockLinkID"]')->attr('value'));

		$LoggedIn = false;
		try {
			$this->followRedirects(false);
			$this->setExpectedStatusCodes([303]);
			$Crawler = $this->submitForm('SubmitUser', array(
				'CompanyNameField' => $_ENV['TEST_DB_SCHEMA'],
				'UserNameEntryField' => $_ENV['TEST_USER_ACCOUNT'],
				'Password' => $_ENV['TEST_USER_PASSWORD']
			));
			$LoggedIn = true;
			$Location = $this->getResponse()->getHeader('Location');
			$this->assertSame($Page, basename(parse_url($Location, PHP_URL_PATH)));
			parse_str(parse_url($Location, PHP_URL_QUERY), $Parameters);
			$this->assertSame($StockID, $Parameters['StockID']);
			$this->setExpectedStatusCodes([200]);
			$Crawler = $this->request('GET', self::$baseUri . '/' . $Page . '?' . parse_url($Location, PHP_URL_QUERY));
			if ($Page == 'SelectProduct.php') {
				$this->assertCount(1, $Crawler->filter('#ProductLink'));
			} else {
				$this->assertSame($StockID, $Crawler->filter('input[name="Part"]')->attr('value'));
			}
		} finally {
			if ($LoggedIn) {
				$this->followRedirects(false);
				$this->setExpectedStatusCodes([302]);
				$this->request('GET', self::$baseUri . '/Logout.php');
			}
		}
	}

	public function testUnapprovedLoginDestinationIsDiscarded(): void
	{
		$this->request('GET', self::$baseUri . '/SelectProduct.php?StockID=PART');
		$Crawler = $this->submitForm('SubmitUser', array(
			'CompanyNameField' => $_ENV['TEST_DB_SCHEMA'],
			'UserNameEntryField' => 'NOUSER' . strtoupper(bin2hex(random_bytes(5))),
			'Password' => 'incorrect',
			'StockLinkPage' => 'https://example.invalid/SelectProduct.php'
		));
		$this->assertStringContainsString('incorrect password', $Crawler->text());
		$this->assertCount(0, $Crawler->filter('input[name="StockLinkPage"]'));
		$this->assertCount(0, $Crawler->filter('input[name="StockLinkID"]'));
	}

	public static function stockLinkPages(): array
	{
		return array(array('SelectProduct.php'), array('BOMIndented.php'));
	}

	/**
	 * Tests access to pages which set $AllowAnyone to true
	 * This test is run many times - the filename to test is provided via the dataProvider.
	 * @dataProvider listAnonAccessPages
	 */
	public function testAccessToAnonPages(string $filePath): void
	{
		if (!is_file(self::$rootDir . '/config.php')) {
			$this->markTestSkipped('config.php is missing. webERP setup has not been done');
		}

		// use the name of the currently tested script as part of the name of the html file saved in case of failure
		$this->executingTestIdentifier = preg_replace('/\.php$/', '', basename($filePath));

		$this->followRedirects(false);
		$this->setExpectedStatusCodes([200]);
		$crawler = $this->request('GET', self::$baseUri . $filePath);

		$this->assertIsNotOnLoginPage($crawler);
	}

	/**
	 * Tests access to pages which do not set $AllowAnyone to true (exclude api, manual, installer)
	 * This test is run many times - the filename to test is provided via the dataProvider.
	 * @dataProvider listNonAnonAccessPages
	 */
	public function testAccessToNonAnonPages(string $filePath): void
	{
		if (!is_file(self::$rootDir . '/config.php')) {
			$this->markTestSkipped('config.php is missing. webERP setup has not been done');
		}

		// use the name of the currently tested script as part of the name of the html file saved in case of failure
		$this->executingTestIdentifier = preg_replace('/\.php$/', '', basename($filePath));

		$this->followRedirects(false);
		$this->setExpectedStatusCodes([200]);
		$crawler = $this->request('GET', self::$baseUri . $filePath);

		$this->assertIsOnLoginPage($crawler);
	}

	/**
	 * Tests that direct access to the installer pages does always result in a redirect.
	 * This test is run many times - the filename to test is provided via the dataProvider.
	 * It can be executed even before the installer has been run.
	 * @dataProvider listInstallerPages
	 */
	public function testDirectAccessToInstallerPages(string $fileName): void
	{
		// avoid phpunit warnings, while ensuring code coverage. The assertions are done by $this->browser
		$this->assertTrue(true);

		// use the name of the currently tested script as part of the name of the html file saved in case of failure
		$this->executingTestIdentifier = preg_replace('/\.php$/', '', basename($fileName));

		$this->followRedirects(false);
		// be tolerant in case in the future we replace the redirect with a page-not-found
		$this->setExpectedStatusCodes([301, 302, 404]);
		$this->request('GET', self::$baseUri . $fileName);
	}

	/**
	 * Tests that direct access to the non-full-pages/non-html-pages does not generate a php warning or error.
	 * This test is run many times - the filename to test is provided via the dataProvider.
	 * @dataProvider listNonWebPages
	 */
	public function testDirectAccessToNonWebPages(string $fileName): void
	{
		// avoid phpunit warnings, while ensuring code coverage. The assertions are done by $this->browser
		$this->assertTrue(true);

		// use the name of the currently tested script as part of the name of the html file saved in case of failure
		$this->executingTestIdentifier = preg_replace('/\.php$/', '', basename($fileName));

		$this->followRedirects(false);
		// be tolerant in case in the future we replace the redirect with a page-not-found
		$this->setExpectedStatusCodes([200, 301, 302, 404]);
		$crawler = $this->request('GET', self::$baseUri . $fileName);
	}

	/**
	 * List all pages of the installer
	 * @return array[]
	 */
	public static function listInstallerPages(): array
	{
		return self::listWebPages([__DIR__ . '/../../install/pages'], true);
	}

	/**
	 * List all pages which set $AllowAnyone to true
	 * @return string[][]
	 */
	public static function listAnonAccessPages(): array
	{
		$pages = [];
		foreach(self::listWebPages() as $path) {
			$fileName = basename($path);
			if (in_array($fileName, ['Logout.php'])) {
				continue;
			}
			if (preg_match('/^\s*\\$AllowAnyone\s*=\s*[Tt][Rr][Uu][eE]\s*;/m', file_get_contents(self::$rootDir . $path))) {
				$pages[] = [$path];
			}
		}
		return $pages;
	}

	/**
	 * List all web pages which do not set $AllowAnyone to true - excluding API, manual, installer.
	 * These are all the pages which should not be accessible by anon user
	 * @return string[][]
	 */
	public static function listNonAnonAccessPages(): array
	{
		$dirs = [
			__DIR__ . '/../../',
			__DIR__ . '/../../dashboard',
			__DIR__ . '/../../reportwriter',
			__DIR__ . '/../../reportwriter/admin',
		];

		$pages = [];
		foreach(self::listWebPages($dirs) as $path) {
			$fileName = basename($path);
			if (in_array($fileName, ['Logout.php', 'config.php', 'config.distrib.php'])) {
				continue;
			}
			if (!preg_match('/^\s*\\$AllowAnyone\s*=\s*[Tt][Rr][Uu][eE]\s*;/m', file_get_contents(self::$rootDir . $path))) {
				$pages[] = [$path];
			}
		}
		return $pages;
	}

	public static function listNonWebPages(): array
	{
		$dirs = [
			__DIR__ . '/../../api',
			//__DIR__ . '/../../bin',
			__DIR__ . '/../../doc/Manual',
			/// @todo once we have fixed all the files in /includes for direct we access, uncomment the line below
			//__DIR__ . '/../../includes',
			/// @todo same for these 3 folder after PR #715 is merged
			//__DIR__ . '/../../reportwriter/admin/forms',
			//__DIR__ . '/../../reportwriter/forms',
			//__DIR__ . '/../../reportwriter/includes',
			__DIR__ . '/../../reportwriter/install',
		];

		return array_merge(
			[
				['/config.php'],
				['/config.distrib.php'],
			],
			self::listWebPages($dirs, true)
		);
	}
}
