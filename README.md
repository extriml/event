EventKit
=====================


## Installation

The package is available on [Packagist](https://packagist.org/packages/elise/events).
You can install it using [Composer](http://getcomposer.org).
```bash
composer require elise/events dev-master
```

```php
require_once("vendor/autoload.php");

use elise\events\Manager as EventManager;
```

Manual
--------------------------

###Initial
```php
$manager = new EventManager;
```


###Option

```php
//Priorities enable
$manager->priority(true);

//Response enable
$manager->response(true);
```

###Create listener closure

```php
$manager->on("db:auth", function($event){
	if ($event->isCancelable() === true) {
		$event->stop();
	}
}, EventManager::NORMAL);
```

###Create listener class
```php
class Db
{
	public function auth($event, $source, $data)
	{
		if ($event->isCancelable() === true) {
			$event->stop();
		}	
	}
}
$manager->on("db",new Db);
```

####`on` parameters
`$event` - object event
`$soruce` - object calling
`$data` -  object calling data
