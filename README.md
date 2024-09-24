# lucite/route

A simple library for mapping api routes to lucite models.

5 methods are provided to setup the following routes:

- GET `/url/`: `->getMany()`
- GET `/url/{id}`: `->getOne()`
- POST `/url/`: `->create()`
- PATCH `/url/{id}`: `->update()`
- DELETE `/url/{delete}`: `->delete()`

Each route returns a psr ResponseInterface with the following json structure in the body:

```
{
  "success": true|false,
  "data": array|object
  "warnings": array,
  "errors": object
}
```


## Installation

`composer require lucite/route`

## Usage

Each route should define 2 static properties:

- `public static string $modelNamespace;`
- `public static string $modelClass;`

Note that `$modelNamespace` is likely the same for all of your routes, so you may want to define this in a parent class that inherits from `Lucite\Route\Route`, which in turn your final route classes inherit from.

## Implementing permissions

Coming soon.