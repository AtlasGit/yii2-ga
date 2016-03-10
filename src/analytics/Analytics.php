<?php
/**
 * Created by PhpStorm.
 * User: eganbarov
 * Date: 3/7/16
 * Time: 16:18
 */
namespace atlasmobile\analytics;

use Yii;
use yii\base\Component;
use yii\base\Exception;

class Analytics extends Component
{
	/**
	 * Начальная дата периода
	 * @var null|string
	 */
	public $startDate = null;

	/**
	 * Конечная дата периода
	 * @var null|string
	 */
	public $endDate = null;

	/**
	 * Тип получаемых данных
	 * @var null|string
	 */
	public $dataType = null;

	/**
	 * Параметры для запроса
	 * @var array
	 */
	public $optParams = [];

	/**
	 * Типы получаемых данных
	 * @var array
	 */
	protected $dataTypes = [
		'sessions'   => 'ga:sessions',
		'users'      => 'ga:users',
		'pageViews'  => 'ga:pageviews',
		'avgSession' => 'ga:avgSessionDuration',
	];

	/**
	 * Инициализация параметров
	 */
	public function init() {
		parent::init();

		$yesterday = date('Y-m-d', strtotime('-1 day'));
		$this->startDate = $this->startDate ?? $yesterday;
		$this->endDate = $this->endDate ?? $yesterday;
		$this->dataType = $this->dataType ?? $this->dataTypes['sessions'];
	}

	/**
	 * Получить данные о сеансах
	 * @param   bool   $isTotal   Флаг (true|false - общую сумму за период|за каждый день)
	 * @return array
	 * @throws Exception
	 */
	public function getSessions($isTotal = true)
	{
		$this->dataType = $this->dataTypes['sessions'];
		$this->optParams = $isTotal ? [] : ['dimensions' => 'ga:date'];

		$data = [];
		foreach ($this->getData() as $result) {
			if ($isTotal) {
				$data = (int)$result[0];
			} else {
				$data[] = [
					date('Y-m-d', strtotime($result[0])),
					(int)$result[1],
				];
			}
		}

		return $data;
	}

	/**
	 * Получить данные о посетителях
	 * @param   bool   $isTotal   Флаг (true|false - общую сумму за период|за каждый день)
	 * @return array
	 * @throws Exception
	 */
	public function getUsers($isTotal = true)
	{
		$this->dataType = $this->dataTypes['users'];
		$this->optParams = $isTotal ? [] : ['dimensions' => 'ga:date'];

		$data = [];
		foreach ($this->getData() as $result) {
			if ($isTotal) {
				$data = (int)$result[0];
			} else {
				$data[] = [
					date('Y-m-d', strtotime($result[0])),
					(int)$result[1],
				];
			}
		}

		return $data;
	}

	/**
	 * Получить данные о просмотренных страницах
	 * @param   bool   $isTotal   Флаг (true|false - общую сумму за период|за каждый день)
	 * @return array
	 * @throws Exception
	 */
	public function getPageViews($isTotal = true)
	{
		$this->dataType = $this->dataTypes['pageViews'];
		$this->optParams = $isTotal ? [] : ['dimensions' => 'ga:date'];

		$data = [];
		foreach ($this->getData() as $result) {
			if ($isTotal) {
				$data = (int)$result[0];
			} else {
				$data[] = [
					date('Y-m-d', strtotime($result[0])),
					(int)$result[1],
				];
			}
		}

		return $data;
	}

	/**
	 * Получить данные о среднем времени сессии
	 * @param   bool   $isTotal   Флаг (true|false - общую сумму за период|за каждый день)
	 * @return array
	 * @throws Exception
	 */
	public function getAvgSessionDuration($isTotal = true)
	{
		$this->dataType = $this->dataTypes['avgSession'];
		$this->optParams = $isTotal ? [] : ['dimensions' => 'ga:date'];

		$data = [];
		foreach ($this->getData() as $result) {
			if ($isTotal) {
				$data = (int)$result[0];
			} else {
				$data[] = [
					date('Y-m-d', strtotime($result[0])),
					(int)$result[1],
				];
			}
		}

		return $data;
	}

	/**
	 * Получить данные по странам и кол-ву людей
	 * @return array
	 * @throws Exception
	 */
	public function getCountries()
	{
		$this->dataType = $this->dataTypes['sessions'];
		$this->optParams = ['dimensions' => 'ga:country', 'sort' => '-ga:sessions', 'max-results' => 10];

		$data = [];
		foreach ($this->getData() as $result) {
			$data[] = [$result[0], $result[1]];
		}

		return $data;
	}


	/**
	 * Универсальный метод для получения основных данных
	 * @return array
	 * @throws Exception
	 */
	public function getData()
	{
		$connection = Connect::getInstance();
		$analyticsId = Yii::$app->params['googleAnalytics']['analyticsId'] ?? null;
		$optParams = $this->optParams ?? [];
		$data = [];

		try {
			$results = $connection->data_ga->get($analyticsId, $this->startDate, $this->endDate, $this->dataType, $optParams);
			if (!isset($results['rows']) || is_null($results['rows'])) {
				$data[] = 0;
			} else {
				foreach ($results['rows'] as $result) {
					$data[] = $result;
				}
			}
		} catch(Exception $e) {
			throw new Exception('Error while fetching data from Google Analytics');
		}

		return $data;
	}
}