# lucite/route

A simple library for mapping api routes to lucite models.


## Installation

`composer require lucite/route`

## Usage

Each route should define 2 static properties:

- `public static string $modelNamespace;`
- `public static string $modelClass;`

Note that `$modelNamespace` is likely the same for all of your routes, so you may want to define this in a parent class that inherits from `Lucite\Route\Route`, which in turn your final route classes inherit from.

## Implementing permissions

Coming soon.