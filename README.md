# Grav Amazon Affiliate Search Link Plugin

`amazon-affiliate-search-link` is a [Grav](http://github.com/getgrav/grav) plugin that adds Amazon affiliate search links in the top, middle and bottom of your pages, automatically, based on the content of your pages, and some other your preferences.

# Installation

Installing the Amazon Affiliate Search Link plugin can be done in one of two ways. Our GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file. 

## GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's Terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install amazon-affiliate-search-link

This will install the plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/amazon-affiliate-search-link`.

## Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `amazon-affiliate-search-link`. You can find these files either on [GitHub](https://github.com/robertotravagliante/amazon-affiliate-search-link) or via [GetGrav.org](http://getgrav.org/downloads/plugins).

You should now have all the plugin files under

    /your/site/grav/user/plugins/amazon-affiliate-search-link

# Updating

As development for the Amazon Affiliate Search Link plugin continues, new versions may become available that add additional features and functionality, improve compatibility with newer Grav releases, and generally provide a better user experience. Updating Amazon Affiliate Search Link is easy, and can be done through Grav's GPM system, as well as manually.

## GPM Update (Preferred)

The simplest way to update this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm). You can do this with this by navigating to the root directory of your Grav install using your system's Terminal (also called command line) and typing the following:

    bin/gpm update amazon-affiliate-search-link

This command will check your Grav install to see if your Amazon Affiliate Search Link plugin is due for an update. If a newer release is found, you will be asked whether or not you wish to update. To continue, type `y` and hit enter. The theme will automatically update and clear Grav's cache.

## Manual Update

Manually updating Amazon Affiliate Search Link is pretty simple. Here is what you will need to do to get this done:

* Delete the `your/site/user/plugins/amazon-affiliate-search-link` directory.
* Download the new version of the Amazon Affiliate Search Link plugin from either [GitHub](https://github.com/robertotravagliante/amazon-affiliate-search-link) or [GetGrav.org](http://getgrav.org/downloads/plugins#extras).
* Unzip the zip file in `your/site/user/plugins` and rename the resulting folder to `amazon-affiliate-search-link`.
* Clear the Grav cache. The simplest way to do this is by going to the root Grav directory in terminal and typing `bin/grav clear-cache`.

> Note: Any changes you have made to any of the files listed under this directory will also be removed and replaced by the new set. Any files located elsewhere (for example a YAML settings file placed in `user/config/plugins`) will remain intact.

# Config Defaults

```
enabled: true
```

# Usage

Once the plugin is installed you can use the admin panel to add:
1. one or more strings to find into the content of the posts or pages;
2. the equivalent strings to use for substitution (containing plain text, HTML, CSS, JS code, and so on...).
