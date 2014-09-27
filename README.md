Drupal Structure
================

Giving Drupal 6 &amp; 7 a Directory Sturcture like Drupal 8 via Composer

This script does the following tasks when Composer `install` and `update` is run:

1. Creates the folders `libraries`, `modules`, `themes`, and `sites` in the `root`
2. Copies `core/sites/README.txt` and `core/sites/example.sites.php` to `sites`
3. Copies `core/sites/default/default.settings.php` to `sites/default/default.settings.php`
4. Removes each folder in `core/sites` that is present in `sites`
5. Creates a symbolic link for each site from `core/sites` to `sites`
6. Copies `core/sites/all/modules/README.txt` to `modules/README.txt`
7. Copies `core/sites/all/themes/README.txt` to `themes/README.txt`
8. Create symbolic link from `core/sites/all/modules` to `modules`
9. Create symbolic link from `core/sites/all/themes` to `themes`
10. Create symbolic link from `core/sites/all/libraries` to `libraries`
11. Create `sites/sites.php` if the file doesn’t already exist
12. Create symbolic link from `core/sites/sites.php` to `sites/sites.php`

# Installation

Require this library in your `composer.json` file

```javascript
{
  "require": {
    "davidbarratt/drupal-structure": "1.0.*@alpha",
  }
}
```

Add the scripts

```javascript
{
  "scripts": {
    "post-install-cmd": [
      "DavidBarratt\\DrupalStructure\\ScriptHandler::postUpdate"
    ],
    "post-update-cmd": [
      "DavidBarratt\\DrupalStructure\\ScriptHandler::postUpdate"
    ]
  }
}
```

# Configuration
By default, the script assumes that Drupal core is located in the `core` directory and the resources (modules, themes, etc.) are located in the same directory as your `composer.json` file.

However, if you’d like to customize this, you can simply add the parameters in the `extra`. Here are the defaults (which are not necessary to add):

```javascript
{
  "extra": {
      "drupal-structure": {
        "root": "",
        "core": "core"
      }
  }
}
```

# Usage
You can use this script by simply executing `composer install` or `composer update`. Composer will execute the script when the process is finished.

# Example
Here is a more practical example of how you might use this script in a real `composer.json` file. This setup assumes that the web root is `core`. However, you could copy `index.php` and `.htaccess` and alter them to fit your needs.

```javascript
{
  "repositories": [
    {
      "type": "composer",
      "url": "http://static.drupal-packagist.org/v0.2.0/"
    }
  ],
  "require": {
    "mnsami/composer-custom-directory-installer": "1.0.*",
    "drupal/drupal": "~7.0",
    "davidbarratt/drupal-structure": "1.0.*@alpha"
  },
  "extra": {
      "installer-paths":{
        "core/": ["drupal/drupal"]
      }
  },
  "scripts": {
    "post-install-cmd": [
      "DavidBarratt\\DrupalStructure\\ScriptHandler::postUpdate"
    ],
    "post-update-cmd": [
      "DavidBarratt\\DrupalStructure\\ScriptHandler::postUpdate"
    ]
  }
}
```

For a more complicated example, please see:
https://github.com/davidbarratt/drupal7/blob/master/composer.json

