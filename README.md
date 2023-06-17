# phpfilemanager

Install:

```
composer require siestacat/phpfilemanager
```

Example:

```
<?php

use Siestacat\Phpfilemanager\File\FileCommander;
use Siestacat\Phpfilemanager\File\Repository\Adapter\FileSystemAdapter;

$commander = new FileCommander(new FileSystemAdapter('./my_data_dir'));

$file = $commander->add('image.jpg');
$file = $commander->get($file->getHash());
if($commander->exists($file->getHash()))
{
    //TO-DO
}
$file = $commander->del($file->getHash());

```

Tests:

```
git clone https://github.com/SiestaCat/phpfilemanager.git
cd phpfilemanager
composer install
composer run-script test
```# phpfilemanager-api-client
