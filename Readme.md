# Sauls Widget

[![Build Status](https://travis-ci.org/sauls/widget.svg?branch=master)](https://travis-ci.org/sauls/widget)
[![Packagist](https://img.shields.io/packagist/v/sauls/widget.svg)](https://packagist.org/packages/sauls/widget)
[![Total Downloads](https://img.shields.io/packagist/dt/sauls/widget.svg)](https://packagist.org/packages/sauls/widget)
[![Coverage Status](https://img.shields.io/coveralls/github/sauls/widget.svg)](https://coveralls.io/github/sauls/widget?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sauls/widget/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sauls/widget/?branch=master)
[![License](https://img.shields.io/github/license/sauls/widget.svg)](https://packagist.org/packages/sauls/widget)

Simple but powerful widget system for PHP

## Requirements

PHP >= 7.2

## Installation

### Using composer
```bash
$ composer require sauls/widget
```
### Apppend the composer.json file manually
```json
{
    "require": {
        "sauls/widget": "^1.0"
    }
}
```

## FAQ

### What is this all about?

This library allows you to crate a standalone, reusable widgets that can be highly customizable for better user-interfaces.  

### What it lacks?

* **Assets** - At the moment it does not have a `js` and `css` management support, but support for `js` and `css` is planned in future versions. 

* **Integrations** - At the moment this library has only `twig` template engine integration.

### What is a Widget?

Widget is a standalone, reusable and highly customizable class that can be used anywhere in the view multiple times with different configurations. There is two types of widgets:

* Widget
* ViewWidget 

### What is a View? 

**View** - is a class that knows how to render widget output. 

### Default Views

* NullView 
* StringView
* PhpFileView
* TwigView

## Documentation

> Keep in mind that this documentation is work in progress as it takes some time to properly write it. At the moment if you don't know where to start looking at the tests folder would be a good start.

### How-to`s

* [How to create a widget?](/doc/how-to/create-widget.md)
* [How to create a view widget?](/doc/how-to/create-view-widget.md)
* How to add own dependencies to widget?
* How to create widget containing other widgets?
* How to setup and use widget factory

### Integrations

* Twig

> If you are using different template engine, and find this library useful feel free to create a pull request with your template engine integration!

