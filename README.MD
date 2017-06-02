# CodebasePHP - A VERY Simple PHP MMVC (Manager Model View Controller) Framework With Full PHP7 Support

### Almost no configuration is required apart from the configs file

## Controllers
```php
<?php

use CodeBase\Controller;
use CodeBase\HttpRequester;


class Home extends Controller 
{
	/** a must have contructor on all controller to access parent features */
	public function __construct($baseClasses) 
	{
		parent::__construct($baseClasses, 'Home');   
	}

	/** all methods that you wish to have a param passed to via browser url 
	 * args must have only one params which is an arrray of all the parameters
	 */
	public function index($params) 
	{
		/** loads \CodeBase\Models\User */
		$user = $this->loadModel('User');
	}
}

```


## Routes
```
Routes are defined in app/routes.php, 
	   Add method accespts 5 params, 3 mandatory and 2 optional
	   first param is the url route, second param is execution method Class@MethodName, third param is the allowed request method
	   fourth param/optional is boolean, true or false to lock acess to this route based on session set
	   fifth param/optional is the session key name that must be set to access this route
	   if you pass 4th param top the add method you must also pass the 5th param

	   <?php
	   /** @file app/routes.php */
	   use CodeBase\Router;

	   if (!defined('CODEBASE')) {
		   die('Direct Access Not Allowed');
	   }


Router::add('/', 'Home@index', ['GET']);
Router::add('account', 'Home@Profile', ['GET'], true, 'userSessionId');
```

### Models (Eloqouent ORM)
```php
<?php

namespace CodeBase\Models;


class User extends \CodeBase\Model 
{

	/** @var array  */
	protected $fillable = [
		'name',
		'age'
	];

	/** @var bool  */
	public $timestamps = false;


	public function addUser($name, $age) 
	{
		User::create([
				'name' => $name,
				'age' => $age
		]);	
	}


}

```
### Models (Standard PDO)
```php
<?php

namespace CodeBase\Models;


class User extends \CodeBase\BASIC_MODEL 
{
	public function __construct()
	{
		parent::__construct();
	}
}

```


### Views
#### From within a controller method call:
```php
$this->view->render('homepage.tpl', $dataArrayToPass);
```

### Application Structure
* app
* controllers
* models
* views
* cache
* compile
* config
* templates
* managers
* routes.php



## Helpers (Libraries)
- Managers
- PdfCreator
- SessionManager
- ErrorHandler
- LoginBoaster
- Emailer
- HttpRequester
- ShoppingCart
- Hooks

#### Feel free to inspect the code for more details as this MVC is still under construction

# Released Open source under MIT Licanse



