# Laravel Has Owner

[![Latest Version](https://img.shields.io/github/v/release/mriembau/laravel-hasowner.svg?style=flat-square)](https://github.com/mriembau/laravel-hasowner/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/mriembau/laravel-hasowner.svg?style=flat-square)](https://packagist.org/packages/mriembau/laravel-hasowner)

This package provides a trait that adds user owned behaviour to an Eloquent model.

By default, any query to the Eloquent model using this trait will only return results where *user_id* is the currently logged in user.

## Support us
You can help us by sending any feedback about our packages to make us know how our open source packages can be improved.

And if you want, you can also buy us a coffe, which will make easier to keep working on this :)

[!["Buy Me A Coffee"](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png)](https://www.buymeacoffee.com/mriembau89)


## Install
This package can be installed through Composer.

`composer require mriembau/laravel-hasowner`


## Configuration

By default, this package uses *id* as a user primary key and *user_id* as foreign key for models owned by a user.
You can override this by publishing the config and changing this values:

`php artisan vendor:publish --provider="Mriembau\LaravelHasOwner\LaravelHasOwnerServiceProvider"`

Change any of the fields in the `has-owner.php` config file:

```php
<?php

return [
    'user_primary_key' => 'id',
    'user_foreign_key' => 'user_id'
];

```

## Usage
To add the owned by user behavior to any of your models, you just have to add the *HasOwner* trait to it.
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mriembau\LaravelHasOwner\Traits\HasOwner;

class MyModel extends Model
{
    use HasOwner;
}

```

With this, any query to *MyModel* will only return elements where *user_id* is the id of the currently authenticated user.

## Change column names for a single model

If you want one of the models not to use the default column names defined in config, you can override them by adding
protected attributes to your model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mriembau\LaravelHasOwner\Traits\HasOwner;

class Payment extends Model
{
    use HasOwner;

    protected $ownerForeignKey = 'client_uuid';
    protected $ownerPrimaryKey = 'uuid';
}

```

## Saving model

When saving a model, if the foreign key referencing to users table is not set, it will be setted to the current user by default.

```php
<?php

// Auth::user()->id == 1

$dummy = Dummy::create([
    'name' => 'Dummy'
]);

// $dummy->user_id == 1
```

If you need to save the model with for a diferent user, you can do it by setting a value for the foreign key:
```php
<?php

// Auth::user()->id == 1

$dummy = Dummy::create([
    'name' => 'Dummy',
    'user_id' => 2
]);

// $dummy->user_id == 2
```

## TO-DOs

You can ask me for more features in case you miss any, by now, I have some in mind which can be helpful:

- Prepare a easy way to access to all results without user filter (for example for backend administration panels)
- Allow "shared" resources, which can be accessed with a different user who has been allowed by the owner (for example:
social network where a user allow his friends to see his posts).

## Changelog

#### 1.0.1
- Tests added
- Improved README

### 1.0.0
- First version

## Credits

- [Marc Riembau](https://github.com/mriembau)
- [All Contributors](https://github.com/mriembau/laravel-hasowner/contributors)

## License
The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.
