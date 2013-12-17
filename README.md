# No cURL Library for [Pushover](https://pushover.net/) 
Also works on Google App Engine
This is a wrapper over the API provided by [Pushover](https://pushover.net/) to send push notification from your application to Andoid & iOS devices. You can check out [full API documentation] (https://pushover.net/api)

To get started, check out <https://pushover.net/>!



## Quick start

Three quick start options are available:

* [Create a Pushover account](https://github.com/twbs/bootstrap/archive/v3.0.3.zip).
* [Create an Application] (https://pushover.net/apps/build).
* Include `pushover.class.php` into your application.
* Start sending push notification.

## How to use ?

### User/Group Validation Example
```
require dirname(__FILE__).'/pushover.class.php';

$pushover = new pushover(array('apiToken' => 'XXxXX'));

if($pushover->validate("userORgroupKEY"))
	echo "User/Group is Valid";
else
	echo "User/Group is InValid";
```
### Notification Sending Example
```
require dirname(__FILE__).'/pushover.class.php';

$pushover = new pushover(array('apiToken' => 'XXxXX'));

$user = "xxxXXXxxx_PUT-YOUR-USER/GROUP-KEY-HERE_xxxXXXxxx";
$message = "Checkout this awesome php5 api for Pushover that doen't even require cURL !";

print_r($pushover->notify($user,$message,$option));

```

Check out the `sample.php` file for sample codes.

# License
The code is licensed under [WTFPL](http://www.wtfpl.net/). Get a copy for yourself [here](http://www.wtfpl.net/txt/copying/).

