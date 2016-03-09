<?php
/**
 * Created by PhpStorm.
 * User: eganbarov
 * Date: 3/7/16
 * Time: 15:49
 */
namespace atlasmobile\analytics;

use Yii;
use Google_Client;
use Google_Service_Analytics;

class Connect
{
	/**
	 * Инстанс подключения к GA
	 * @var null
	 */
	private static $_instance = null;

	/**
	 * Получить инстанс подключения к GA
	 * @return null|Google_Service_Analytics
	 */
	public static function getInstance()
	{
		if (static::$_instance === null) {
			static::$_instance = static::connect();
		}

		return static::$_instance;
	}

	private function __construct() {}
	private function __clone() {}

	/**
	 * Подключиться к GA
	 * @return \Google_Service_Analytics
	 */
	private static function connect()
	{
		$gaParams = Yii::$app->params['googleAnalytics'] ?? [];

		$developerKey = $gaParams['developerKey'] ?? null;
		$clientId = $gaParams['clientId'] ?? null;
		$clientEmail = $gaParams['serviceAccountName'] ?? null;
		$privateKeyPath = Yii::getAlias($gaParams['privateKeyPath'] ?? '');
		$scopes = ['https://www.googleapis.com/auth/analytics.readonly'];

		$client = new Google_Client();
		$client->setApplicationName('Atlas');
		$client->setDeveloperKey($developerKey);

		$credentials = new \Google_Auth_AssertionCredentials(
			$clientEmail,
			$scopes,
			file_get_contents(Yii::getAlias($privateKeyPath))
		);

		$client->setAssertionCredentials($credentials);
		$client->setClientId($clientId);
		$client->setAccessType('offline_access');

		return new Google_Service_Analytics($client);
	}
}