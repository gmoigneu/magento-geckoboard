magento-geckoboard
===============

Geckoboard (http://www.geckoboard.com/) module for Magento

## Features

![Config](https://raw.github.com/gmoigneu/magento-geckoboard/doc/features.png)

- Daily Turnover (compared to yesterday's) - Number & secondary stat widget
- Monthly Turnover (compared to last month) - Number & secondary stat widget
- Today orders count - Number & secondary stat widget
- Today orders pending - Number & secondary stat widget
- Last order details - Text widget
- Newsletters subscribers count - Number & secondary stat widget

## Installation

### Prerequisites

- libcurl
- php5-curl

### With [modman](https://github.com/colinmollenhour/modman) :

    modman clone git@github.com:gmoigneu/magento-geckoboard.git

### Manually :

- Download master zipball : https://github.com/gmoigneu/magento-geckoboard/archive/master.zip
- Unzip in a temp folder
- Put code/* into magento_folder/app/code/community/Nls/Geckoboard
- Put Nls\_Geckoboard.xml in magento_folder/app/etc/modules
- Clear your config cache

## Setup

The extension configuration is located in **Configuration > Nls > Geckoboard**

You need to fill the API Key that can be found in your Geckoboard account and setup the base URL : https://push.geckoboard.com/v1/send/

Create custom widgets in your Geckoboard Dashboard that match the type of the widgets available in the module. Input the Widget ID into the configuration panel :

![Config](https://raw.github.com/gmoigneu/magento-geckoboard/doc/magentoconfig.png)

**The update is asynchronous every minute through the Magento cron system.**

## Todo

- Use Guzzle instead of curl
- Use more sophisticated widgets
