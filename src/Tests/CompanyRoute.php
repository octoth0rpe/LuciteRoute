<?php

declare(strict_types=1);

namespace Lucite\Route\Tests;

use Lucite\Route\Route;
use Lucite\Model\NoPermissionCheckTrait;

class CompanyRoute extends Route
{
    use NoPermissionCheckTrait;
    public static string $modelNamespace = 'Lucite\Route\Tests';
    public static string $modelClass = 'CompanyModel';
}
