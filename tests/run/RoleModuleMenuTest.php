<?php

require_once(__DIR__ . '/../src/LoggedInUserTestCase.php');

class RoleModuleMenuTest extends LoggedInUserTestCase
{
	public function testMenuUsesRoleModulesAndFallsBackFromInvalidSelection(): void
	{
		$db = new mysqli(
			$_ENV['TEST_DB_HOSTNAME'],
			$_ENV['TEST_DB_USER'],
			$_ENV['TEST_DB_PASSWORD'],
			$_ENV['TEST_DB_SCHEMA'],
			$_ENV['TEST_DB_PORT']
		);
		$statement = $db->prepare('SELECT fullaccess FROM www_users WHERE userid = ?');
		$statement->bind_param('s', $_ENV['TEST_USER_ACCOUNT']);
		$statement->execute();
		$user = $statement->get_result()->fetch_assoc();
		$this->assertNotNull($user);

		$statement = $db->prepare('SELECT tokenid FROM securitygroups WHERE secroleid = ?');
		$statement->bind_param('s', $user['fullaccess']);
		$statement->execute();
		$tokens = array_column($statement->get_result()->fetch_all(MYSQLI_ASSOC), 'tokenid');
		if (count($tokens) == 2 && (in_array(1, $tokens) || in_array(9, $tokens))) {
			$this->markTestSkipped('Customer and supplier logins use a different menu');
		}

		$statement = $db->prepare('SELECT modulelink FROM modules WHERE secroleid = ? ORDER BY sequence');
		$statement->bind_param('s', $user['fullaccess']);
		$statement->execute();
		$roleModules = array_column($statement->get_result()->fetch_all(MYSQLI_ASSOC), 'modulelink');
		$statement = $db->prepare('SELECT modulelink, menusection, url FROM menuitems WHERE secroleid = ?');
		$statement->bind_param('s', $user['fullaccess']);
		$statement->execute();
		$items = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
		$security = array_column($db->query('SELECT script, pagesecurity FROM scripts')->fetch_all(MYSQLI_ASSOC), 'pagesecurity', 'script');
		$modules = array();
		foreach ($roleModules as $module) {
			foreach ($items as $item) {
				$script = basename(explode('?', $item['url'], 2)[0]);
				if ($item['modulelink'] == $module
					&& in_array($item['menusection'], array('Transactions', 'Reports', 'Maintenance'))
					&& isset($security[$script])
					&& $security[$script] !== ''
					&& in_array($security[$script], $tokens)) {
					$modules[] = $module;
					break;
				}
			}
		}

		$crawler = $this->request('GET', self::$baseUri . '/index.php?Application=invalid-module-selection');
		$links = $crawler->filter('nav.ModuleList li a')->each(function ($node) {
			parse_str(parse_url($node->attr('href'), PHP_URL_QUERY), $query);
			return $query['Application'];
		});
		$this->assertSame($modules, $links);
		if (count($modules) > 0) {
			$selected = $crawler->filter('nav.ModuleList li.ModuleSelected a');
			$this->assertCount(1, $selected);
			parse_str(parse_url($selected->attr('href'), PHP_URL_QUERY), $query);
			$this->assertSame($modules[0], $query['Application']);
		} else {
			$this->assertCount(0, $crawler->filter('nav.ModuleList li.ModuleSelected'));
		}

		$hiddenModules = array_values(array_diff($roleModules, $modules));
		if (count($hiddenModules) > 0) {
			$crawler = $this->request('GET', self::$baseUri . '/index.php?Application=' . urlencode($hiddenModules[0]));
			$selected = $crawler->filter('nav.ModuleList li.ModuleSelected a');
			if (count($modules) > 0) {
				$this->assertCount(1, $selected);
				parse_str(parse_url($selected->attr('href'), PHP_URL_QUERY), $query);
				$this->assertSame($modules[0], $query['Application']);
			} else {
				$this->assertCount(0, $selected);
			}
		}
	}
}
