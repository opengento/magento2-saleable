# Saleable Module for Magento 2

[![Latest Stable Version](https://img.shields.io/packagist/v/opengento/module-saleable.svg?style=flat-square)](https://packagist.org/packages/opengento/module-saleable)
[![License: MIT](https://img.shields.io/github/license/opengento/magento2-saleable.svg?style=flat-square)](./LICENSE) 
[![Packagist](https://img.shields.io/packagist/dt/opengento/module-saleable.svg?style=flat-square)](https://packagist.org/packages/opengento/module-saleable/stats)
[![Packagist](https://img.shields.io/packagist/dm/opengento/module-saleable.svg?style=flat-square)](https://packagist.org/packages/opengento/module-saleable/stats)

This extension allows to set if a product is saleable and can show its price by scope and customer group.

 - [Setup](#setup)
   - [Composer installation](#composer-installation)
   - [Setup the module](#setup-the-module)
 - [Features](#features)
 - [Settings](#settings)
 - [Support](#support)
 - [Authors](#authors)
 - [License](#license)

## Setup

Magento 2 Open Source or Commerce edition is required.

###  Composer installation

Run the following composer command:

```
composer require opengento/module-saleable
```

### Setup the module

Run the following magento command:

```
bin/magento setup:upgrade
```

**If you are in production mode, do not forget to recompile and redeploy the static resources.**

## Features

### Saleable

- Define if the price can be displayed on the storefront, depending of the customer group and by scope.
- Define if the sales are enabled on the website and by customer groups.

## Settings

The configuration for this module is available in 'Stores > Configuration > Catalog > Catalog > Price'.

- Show Prices for Customer Groups

The configuration for this module is available in 'Stores > Configuration > Sales > Checkout > Shopping Cart'.

- Enable Sales for Customer Groups

### Warning

If you need to determine the rules by products, create new product attributes:

- can_show_price (yes/no) The module has a plugin to enforce the type result to be a boolean.
- salable (yes/no) The module has a plugin to enforce the type result to be a boolean.

Magento will automatically handle these attributes to check if a product is saleable or its price can be displayed.

## Support

Raise a new [request](https://github.com/opengento/magento2-saleable/issues) to the issue tracker.

## Authors

- **Opengento Community** - *Lead* - [![Twitter Follow](https://img.shields.io/twitter/follow/opengento.svg?style=social)](https://twitter.com/opengento)
- **Thomas Klein** - *Maintainer* - [![GitHub followers](https://img.shields.io/github/followers/thomas-kl1.svg?style=social)](https://github.com/thomas-kl1)
- **Contributors** - *Contributor* - [![GitHub contributors](https://img.shields.io/github/contributors/opengento/magento2-saleable.svg?style=flat-square)](https://github.com/opengento/magento2-saleable/graphs/contributors)

## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) details.

***That's all folks!***
