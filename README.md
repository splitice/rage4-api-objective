Rage4 DNS Objective Wrapping
============================

[![Build Status](https://travis-ci.org/splitice/rage4-api-objective.svg?branch=master)](https://travis-ci.org/splitice/rage4-api-objective)

This is a PHP5 wrapper to map Rage4 objects to domain objects and synchronize changes made.

## Requirements
You need PHP 5.3.2+ compiled with the cURL extension.

## Install Rage4 DNS Objective Wrapping
### Installing via Composer

The recommended way to install OVH SDK is through [Composer](http://getcomposer.org).

1. Add ``splitice/rage4-api-objective`` as a dependency in your project's ``composer.json`` file:

        {
            "require": {
                "splitice/rage4-api-objective": "dev-master"
            }
        }

2. Download and install Composer:

        curl -s http://getcomposer.org/installer | php

3. Install your dependencies:

        php composer.phar install

4. Require Composer's autoloader

    Composer also prepares an autoload file that's capable of autoloading all of the classes in any of the libraries that it downloads. To use it, just add the following line to your code's bootstrap process:

        require 'vendor/autoload.php';

You can find out more on how to install Composer, configure autoloading, and other best-practices for defining dependencies at [getcomposer.org](http://getcomposer.org).

## Examples

Here are some examples on how to do basic operations.

## List all Domains
TODO

## Get domain by name
TODO

## Add / Update records
TODO

## More
See the unit tests for more usage examples