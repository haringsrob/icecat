Icecat
======
[![Build Status](https://travis-ci.org/haringsrob/icecat.svg?branch=master)](https://travis-ci.org/haringsrob/icecat)
[![Code Climate](https://codeclimate.com/github/haringsrob/icecat/badges/gpa.svg)](https://codeclimate.com/github/haringsrob/icecat)

Icecat is a PHP library, that assists you in the following 2 procedures:
* Fetching data from the Icecat database using basic product information.
* Parsing this data from the Icecat response, and using them in real life applications.

### About Icecat
[Icecat](http://icecat.biz, "Icecat") is an open catalog, providing free access to thousands of product datasheets.
In extend, when taking a subscription, the amount of accessible datasheets are increased.

There is a list of [Icecat sponsor brands](http://icecat.co.uk/en/menu/partners/index.html, "Icecat sponsor brands").


Installation
============

The library can be installed using composer:

```
"haringsrob/icecat": "dev-master"
```

Usage
=====

The class library is, in it's current state easy to be used.

### Result

The [Icecat class](https://github.com/haringsrob/icecat/blob/master/src/Model/Icecat.php) is responsible for parsing the data. It includes a few basic methods, but you can easily create your 
own implementation by implementing the IcecatInterface interface.

```php
// Use the class.
use haringsrob\Icecat\Model\Result;

// See IcecatFetcher on how to get data or implement your own way.
$data = $fetcher->getBaseData();

// Initialize.
$icecat = new Result($data);

// Brand name. e.g.: Acer
$icecat->getSupplier();

// Long description of the product.
$icecat->getLongDescription();

// Short description.
$icecat->getShortDescription();

// The category the product belongs to. e.g.: Notebooks
$icecat->getCategory();

// Returns maximum 5 images about the product (optional limit).
$icecat->getImages(5);

// Returns key => value array with specifications. e.g: ['cpu' => 'Core I5', 'screensize' => '15.6']
$icecat->getSpecs();
```

Demo is soon available.

### Fetcher

The [IcecatFetcher](https://github.com/haringsrob/icecat/blob/master/src/Model/IcecatFetcher.php) is responsible for fetching the data from the database.

```php
// Use the class.
use haringsrob\Icecat\Model\Fetcher;

// Inititialize.
$fetcher = new Fetcher(
    'Username',
    'Password',
    'Ean',
    'Language'
);

// Fetch the actual data.
$data = $fetcher->fetchBaseData();
```

Integrations
============

[Drupal module](https://www.drupal.org/sandbox/tortelduif/2669832, "Icecat Drupal") under active development.
