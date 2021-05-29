![Build Status](https://github.com/gielfeldt/fas-configuration/actions/workflows/test.yml/badge.svg)
![Test Coverage](https://img.shields.io/endpoint?url=https://gist.githubusercontent.com/gielfeldt/ac056829ce43de32d37257c91a7635e5/raw/fas-configuration__main.json)

![Latest Stable Version](https://poser.pugx.org/fas/configuration/v/stable.svg)
![Latest Unstable Version](https://poser.pugx.org/fas/configuration/v/unstable.svg)
![License](https://poser.pugx.org/fas/configuration/license.svg)
![Total Downloads](https://poser.pugx.org/fas/configuration/downloads.svg)

# Installation

```bash
composer require fas/configuration
```


# Usage

A simple configuration file library.

```yaml
database:
    host: mydbhost
```

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Fas\Configuration\DotNotation;
use Fas\Configuration\YamlLoader;

$configuration = new DotNotation(YamlLoader::loadWithOverrides('/app/config.yml'));
var_dump($configuration->require('database.host'));

```

Output:

```
/app/test.php:9:
string(8) "mydbhost"
```
