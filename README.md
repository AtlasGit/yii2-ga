# yii2-ga
Google analytics component for Yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist atlasmobile/yii2-ga "*"
```

or add

```json
"atlasmobile/yii2-ga": "*"
```

to the require section of your `composer.json` file.

Application params
------------------

To use this extension, add the following code in your application params:

```php
return [
    ...
    'googleAnalytics' => [
        'developerKey' 		 => '', // Public key
        'clientId' 			 => 'xxx.apps.googleusercontent.com', // Client ID
        'analyticsId'        => 'ga:xxxxxxxxx', //(It is the number at the end of the URL starting with p: https://www.google.com/analytics/web/#home/a33443w112345pXXXXXXXX/)
        'serviceAccountName' => 'xxx@xxx.gserviceaccount.com', // Email address
        'privateKeyPath'	 => '', //path to private key in p12 format
    ],
];
```

Add the serviceAccountName (xxx@dxxx.gserviceaccount.com) as a new user to your Analyics property.

Usage
-----

```php

use atlasmobile\analytics\Analytics;

class Test
{
	public function example()
	{
		$analytics = new Analytics();
		$analytics->startDate = '';
		$analytics->endDate = '';
		
		$sessionsData = $analytics->getSessions();
		$visitorsData = $analytics->getUsers();
		$pageViewsData = $analytics->getPageViews();
		$avgSessionsDurationData = $analytics->getAvgSessionDuration();
		$countriesData = $analytics->getCountries();
	}
}

```

Parameters 'startDate' and 'endDate' are set by default for yesterday

Useful links
------------

[Analytics Core Reporting API](https://developers.google.com/analytics/devguides/reporting/core/dimsmets)  
[Google API Php Client](https://github.com/google/google-api-php-client)  
