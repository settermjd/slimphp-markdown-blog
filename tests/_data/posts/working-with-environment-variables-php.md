---
publish_date: 14.10.2021
slug: environment-variables-php
title: Working with Environment Variables in PHP
synopsis: In this tutorial, learn how environment variables can be set and retrieved in PHP applications.
image: environment-variables-php.png
categories:
- PHP
tags:
- Environment variables
- Configuration
---
Environment variables are an excellent way to configure PHP applications because they keep the application’s settings outside of the code.
By doing this, it's easier to prevent secure credentials from being exposed, maintain applications, and use applications across multiple environments.

In this tutorial, you're going to learn about some of the many ways in which environment variables can be set and retrieved in PHP applications.
That way, your application can access all the information that it needs, such as API keys, uploaded files, query strings, and form data.

## How to access environment variables in PHP

### Use PHP's Superglobals

One of the most common ways that environment variables are accessed in PHP is through the use of [Superglobals](https://www.php.net/manual/en/language.variables.superglobals.php).
These are built-in, predefined variables, available in all scopes.
Initialised by the PHP runtime, they organise PHP's environment information in a (mostly) logical and efficient way, so that you only need to consult one array to retrieve the information you need.

For example, `$_SERVER` contains request headers, paths, and script locations, `$_SESSION` contains session variables, and `$_POST` contains variables passed to the current script when called with the HTTP POST method.

That said, there are some things to be aware of.

- Firstly, depending on how [the `variables_order` directive](https://www.php.net/manual/en/ini.core.php#ini.variables-order) is set, one or more of the Superglobal arrays may be empty. This is important to check, if your application depends on a given Superglobal being populated.
- Secondly, the variables contained in `$_SERVER` and `$_ENV` (which contains variables imported from the environment under which the PHP parser is running) can overlap with one another, depending on the script's context. This might be confusing if you were expecting the keys to be unique across all the Superglobals.
