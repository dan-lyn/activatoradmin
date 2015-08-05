#ActivatorAdmin

[![Build Status](https://secure.travis-ci.org/dan-lyn/activatoradmin.png?branch=master)](http://travis-ci.org/dan-lyn/activatoradmin)

##Third-Party Libraries

- Slim Framework 2.6.1 (https://github.com/codeguy/Slim)
- Backbone.js 1.2.1 (http://backbonejs.org/)
- backbone.paginator 2.0.2 (https://github.com/backbone-paginator/backbone.paginator)
- jQuery 2.1.4 (http://jquery.com/)
- Bootstrap 3.3.5 (http://getbootstrap.com/)
- Font Awesome 4.4.0 (http://fortawesome.github.io/Font-Awesome/)
- RequireJS 2.1.20 (http://requirejs.org/docs/download.html)
- RequireJS text 2.0.14 (https://github.com/requirejs/text)
- Monolog 1.15.0 (https://github.com/Seldaek/monolog)
- PSR Log 1.0.0 (https://github.com/php-fig/log)
- grunt 0.4.5 (http://gruntjs.com/)
- grunt-contrib-uglify 0.9.1 (https://github.com/gruntjs/grunt-contrib-uglify)
- grunt-contrib-cssmin 0.12.3 (https://github.com/gruntjs/grunt-contrib-cssmin)
- grunt-contrib-jasmine 0.9.0 (https://github.com/gruntjs/grunt-contrib-jasmine)
- grunt-template-jasmine-requirejs 0.2.3 (https://github.com/cloudchen/grunt-template-jasmine-requirejs)
- grunt-phpunit 0.3.6 (https://github.com/SaschaGalley/grunt-phpunit)
- grunt-contrib-watch 0.6.1 (https://github.com/gruntjs/grunt-contrib-watch)
- grunt-contrib-jshint 0.11.2 (https://github.com/gruntjs/grunt-contrib-jshint)
- grunt-contrib-compass 1.0.3 (https://github.com/gruntjs/grunt-contrib-compass)
- grunt-contrib-concat 0.5.1 (https://github.com/gruntjs/grunt-contrib-concat)
- grunt-casperjs 2.1.0 (https://github.com/ronaldlokers/grunt-casperjs)
- grunt-contrib-requirejs 0.4.4 (https://github.com/gruntjs/grunt-contrib-requirejs)
- gulp 3.9.0 (https://github.com/gulpjs/gulp/)
- gulp-concat 2.6.0 (https://github.com/wearefractal/gulp-concat)
- gulp-uglify 1.2.0 (https://github.com/terinjokes/gulp-uglify/)

##Usage

Login using these fixed credentials: Username = admin & Password = admin. The credentials are set in config/config.ini.

A default database structure can be found in docs/db.sql. Use database mapping as explained under Configuration if the default database structure is different.

##Configuration

Setup the configuration in config/config.ini and config/config.js. Changes to config/config.js should be followed by "grunt uglify" for minifying the javascript.

Mapping of column names in tables allows changing the names for "name", "isactive", and "image" to whatever matches the table in use. It must be set up in both configuration files!
```
name = "name"
isactive = "isactive"
image = "image"
```

Setting up the host and baseurl must also be specified in both configuration files. Baseurl is only needed if ActivatorAdmin is not located in the root directory. Here is the format of "host" and "baseurl" - notice that the protocol http(s) is not needed:
```
host: 'localhost'
baseUrl: '/activatoradmin/'
```

It is possible to enable and disable certain frontend features using some configuration variables in config.js:
```
showInfo: true // Show info button for each item
showDelete: true // Show delete button for deleting a single item

```

Enable/Disable logging in config/config.ini: (logging to docs/activatoradmin.log)
```
[logging]
log = 0
```

##Documentation

See API Documentation generated by phpDocumentor v2.5.0 at ROOT_DIRECTORY/docs/phpdoc/

See the results from PHPLOC at ROOT_DIRECTORY/docs/phploc.csv. Update the results by running update_phploc.sh.

##Testing

Test cases have been created and tested using Jasmine v2.0.0, CasperJS v1.1.0-beta3, and PHPUnit 4.0.20.

Run both Jasmine, CasperJS, and PHPUnit test cases using grunt:
```
grunt test
```

Run PHPUnit test cases from ROOT_DIRECTORY:
```
phpunit test/phpunit
```

Run Jasmine test cases at ROOT_DIRECTORY/test/jasmine/SpecRunner.html

Run CasperJS test cases from ROOT_DIRECTORY:
```
casperjs test test/casperjs
```

##License

ActivatorAdmin is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
