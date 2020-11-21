## 1. Requirements:

* **Bagisto**: v1.2.x

## 2. Installation:


#### Goto config/app.php file and add following line under 'providers'

~~~
EDES\SaasAdmin\Providers\SaasAdminServiceProvider::class
~~~


#### Goto composer.json file and add following line under 'psr-4':

~~~
"EDES\\SaasAdmin\\": "packages/EDES/SaasAdmin/src"
~~~


#### Run the below mentioned commands from the root directory in terminal:

~~~
1. composer dump-autoload
~~~

