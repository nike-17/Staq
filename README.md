Staq [![Build Status](https://travis-ci.org/Pixel418/Staq.png?branch=master)](https://travis-ci.org/Pixel418/Staq?branch=master)
======

Staq is a modern & innovative PHP framework for enjoyable web development.

1. [Features](#features)
2. [Let's code](#lets-code)
3. [How to Install](#how-to-install)
4. [How to Contribute](#how-to-contribute)
5. [Author & Community](#author--community)



Features
--------

Staq contains all the expected features : Extensible structure, routing, ORM, templating & pre-coded applications ( *Planned* ). <br>
It mainly contains a new object pattern, *the stack*, for low dependency, high extensible & enjoyable development !

[&uarr; top](#readme)



Let's code
--------

### Hello world tutorial 

```php
require_once( 'vendor/pixel418/staq/src/include.php' );

\Staq\App::create( )
    ->addController( '/hello/:name', function( $name ) {
        return 'Hello ' . $name;
    } )
    ->run( );
```


### Hello world projects

You can use the Hello World projects to bootstrap an application:

 * The simplest [Staq Hello World project](https://github.com/Pixel418/Staq-HelloWorld/)
 * The [Staq Hello World project with MVC structure](https://github.com/Pixel418/Staq-HelloWorldMVC/)


### System Requirements
You need **PHP >= 5.4** and some happiness.

[&uarr; top](#readme)



How to Install
--------

If you don't have composer, you have to [install it](http://getcomposer.org/doc/01-basic-usage.md#installation).

Add or complete the composer.json file at the root of your repository, like this :

```json
{
    "require": {
        "pixel418/staq": "0.6.0"
    }
}
```

Staq can now be [downloaded via composer](http://getcomposer.org/doc/01-basic-usage.md#installing-dependencies).

The framework and the extensions work with the composer autoload:

```php
require_once( './vendor/autoload.php' );
```

[&uarr; top](#readme)



How to Contribute
--------

1. Fork the Staq repository
2. Create a new branch for each feature or improvement
3. Send a pull request from each feature branch to the **develop** branch

If you don't know much about pull request, you can read [the Github article](https://help.github.com/articles/using-pull-requests).

All pull requests must follow the [PSR2 standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) and be accompanied by passing [phpunit](https://github.com/sebastianbergmann/phpunit/) tests.

[&uarr; top](#readme)



Author & Community
--------

Staq is under [MIT License](http://opensource.org/licenses/MIT).  
It is created and maintained by [Thomas ZILLIOX](http://tzi.fr).  
If you have a question, you can send a message to the community via [the mailing list](mailto:staq-project@googlegroups.com).  
If you are curious on the next features, you can see my [trello board](https://trello.com/board/staq/50de3fe18942735c620000a9).

[&uarr; top](#readme)
