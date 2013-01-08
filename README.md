Staq
======
Staq is a small PHP framework for a enjoyable web development.

### Features

Staq contains all the needed features : Extensible structure, routing, ORM ( *Planned* ), templating ( *Planned* ) & pre-coded applications ( *Planned* ). <br>
Staq mainly contains a new object pattern, *the stack*, for low dependency, high extensible & enjoyable development !

### License

Staq is under [MIT License](http://opensource.org/licenses/MIT)

### Community

Staq is created and maintained by [Thomas ZILLIOX](http://zilliox.me). <br>
If you have a question, you can send a message to the community via [the mailing list](mailto:staq-project@googlegroups.com). 



Let's coding
--------

### Hello world tutorial 

```php
require_once( 'path/to/Staq/include.php' );

\Staq\Application::create( 'Hello_World' )
    ->add_controller( '/*', function( ) {
        return 'Hello World';
    } )
    ->run( );
```

### System Requirements
You need **PHP >= 5.4** and some happiness.



Roadmap
--------
The last stable version is [v0.3](https://github.com/Pixel418/Staq).

I am working hard on the [v0.4](https://github.com/Pixel418/Staq/tree/develop). <br>
If you are curious on the next features, you can see my [trello board](https://trello.com/board/staq/50de3fe18942735c620000a9).
