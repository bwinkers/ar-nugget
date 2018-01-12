# nugget
[![Coverage Status](https://coveralls.io/repos/github/bwinkers/nugget/badge.svg?branch=master)](https://coveralls.io/github/bwinkers/nugget?branch=master)

A PHP7 for defining, validating and processing JSON business objects.

The ActiveRules Nugget Schema treats all data as `nuggets`.
These `nuggets` can be defined, and related using JSON files.
Rules can then be applied to any changes to an object.

## Testing 

## Local only tests (run by Travis CI)

```php vendor/bin/phpunit```

## Using remote schema (broken on Travis CI)

```php vendor/bin/phpunit tests/Activerules/Nugget/NuggetTest-Dev.php --testdox```

## Generate Property definitions from CSV files

Import property name, type and description from a CSV file.
More options will be added later.

```
php src/bin/convertCSVToProperties.php
```

## Generate Open API schema objects

ActiveRules obejct definitions are converted to Open API schema objects composed of property defintions.

```
php src/bin/convertObjectsToSchema.php
```

```
php src/bin/readPropertiesFromGoogle.php -s "1NeU79bJ-Zic-fwKK2PPuxntNXnbkyMKf6ZTIUHp1n4s" -c ~/.google/izzup-client_secret.json
```