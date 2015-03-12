Event
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


//Getting responses
$manager->getResponses();//return array
```

###Listeners
####Create listener closure

```php
$manager->on("db:connect", function($event){
	echo "DB Connected";
	if ($event->isCancelable() === true) {
		$event->stop();
	}
	return "#1"; 
}, EventManager::NORMAL);
```

####Create listener class
```php
class DbEvent
{
	public function connect($event, $source, $data)
	{
		echo "DB Connected";
		if ($event->isCancelable() === true) {
			$event->stop();
		}
		return "#2";
	}
}
$manager->on("db",new Db);
```

```php
$manager->on($eventType,$listener,$priority);
```
#####`on` parameters
`$eventType` - Event name
`$listener` - Listener object or closure
`$priority` - Priority runing event

#####Priority variants
* EventManager::LOW
* EventManager::NORMAL
* EventManager::HIGH
* any integer

#####`listener` parameters

`$event` - object event
`$soruce` - object calling or null
`$data` -  object calling data (array)

###Event
####Lazy
```php
$manager->run("db:connect");
```

####Full
```php
class Db
{
	function connect()
	{
		$manager->run("db:connect", $this, array(), false);
	}
}
```

#####`run` parameters

```php
$manager->run($eventType, $source, $data, $cancelabe);
```
`$eventType` - name running event
`$soruce` - Calling class object or null
`$data` - data setting event listener
`$cancelable` - is canceling event


####Allowed
```php
//Local disable
$manager->allowedListeners("db:connect",false);

//Global disable
$manager->allowedListeners("db",false);
```


####Methods

###Dettach
```php
$manager->dettch("db");
//or
$manager->detach("db");
```

###Has listeners
```php
$manager->hasListeners("db"); //return bool
```

###Get Listeners
```php
$manager->getListeners("db");//return array
```
