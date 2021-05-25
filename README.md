

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
