# Candide - Lightweight PHP CMS for webdevelopers
Candide generate automaticaly an admin platform according to your php code. It's lightweight, easily extensible with plugins, and it uses a flatfile architecture therefore you don't need any database.

![Candide PHP Flatfiles banner](https://github.com/RomainPct/Candide_PHP_FlatFiles_CMS/blob/master/.github/readme_banner.png)

## Table of Contents  
1. [What's Candide ?](#what_is_candide)  
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [How to use Candide ?](#how_to_use)
    1. [Create a Candide Page](#how_to_use_page)
    2. [Create a Candide Collection](#how_to_use_collection)
    3. [Create a Candide Collection Item](#how_to_use_collection_item)
    4. [Multi Candide instances on a single page](#how_to_use_multi_candide_instances)
    5. [Candide native types](#how_to_use_types)
5. [Configure Candide](#configuration)
5. [Admin platform autogeneration](#autogeneration)
6. [Ho to work with plugins ?](#plugins)
    1. [How to install plugins ?](#plugins_installation)
    2. [Visual Interface plugin](#plugins_visual_interface)
    3. [Backend actions plugin](#plugins_backend_actions)
    4. [Candide Class Extension plugin](#plugins_class_extension)
7. [From local to production server](#local_to_prod)
8. [Candide Installation Assistance](#assistance)
9. [License](#license)

<a name="what_is_candide"/>

## What's Candide ?
Candide is a CMS made for developers. All settings are made directly in your php code and your code generate automatically an admin interface with the fields you need. It's an easy solution to implement which allow you to build website without a cumbersome framework while benefiting from a administration platform for your client.

Just tap on a button, and your administration interface is ready !

![Candide admin platform generation](https://github.com/RomainPct/Candide_PHP_FlatFiles_CMS/blob/master/.github/readme_code_candide_interface.png)

Candide CMS is really efficient thank's to a really simple structure which allow you to organize your project as you want. It's also lightweight and use minimal components as the really lightweihgt WYSIWYG editor : Pell (3.54kB).

It's also a flat files CMS, therefore you don't need to manage a database at all. Just manage files.
And finally, it's extensible thank's to an infinite number of plugin possibilities. You can get general plugins or create you own custom plugin for specific uses.

This project is free to use but you can support it by making a donation !  
[![Support Candide via PayPal](https://github.com/RomainPct/Candide_PHP_FlatFiles_CMS/blob/master/.github/readme_donation_button.png)](https://www.paypal.me/romainpenchenat/)

<a name="requirements"/>

## Requirements
Candide has to run on a PHP 7 server or a later version.

To use it, you only need really basic PHP notions (The 3 first modules of this [PHP course](https://openclassrooms.com/fr/courses/918836-concevez-votre-site-web-avec-php-et-mysql) is largely enough to use Candide).

<a name="installation"/>

## Installation
1. Download the Candide project from [Github](https://github.com/RomainPct/Candide_PHP_FlatFiles_CMS)
2. Copy folder called "admin" in the "Source" directory
3. Paste it at the root of your website project

<a name="how_to_use"/>

# How to use Candide ?

<a name="how_to_use_page"/>

## Create a classic page with Candide ?

1. Import Candide.php at the beginning of your webpage
2. Instancy a CandidePage in a variable called $c, it only requires a single parameter : the name of your page
3. Open PHP balise when needed and call the type function where you need the content

```php
<?php
include 'admin/Candide.php';
$c = new CandidePage('your_page_name_in_snake_case');
?>
<html>
    <head>
        <title><?= $c->text('title') ?></title>
    </head>
    <body>
        <img src="<?= $c->image('banner',[1080,200]) ?>" alt="Website banner">
        <h1><?= $c->text('title') ?></h1>
        <p><?= $c->text('content',true) ?></p>
    </body>
</html>
```

<a name="how_to_use_collection"/>

## Create a Candide Collection
1. Import Candide.php at the beginning of your webpage
2. Instancy a CandideCollection in a variable called $c, it only requires a single parameter : the name of your collection
3. Iterate in the $c->items() to get each item of the collection
4. Open PHP balise when needed and call the type function on the item where you need the content
5. You can call the id() function on an item to get its id

```php
<?php
include 'admin/Candide.php';
$c = new CandideCollection('your_collection_name_in_snake_case');
?>
<html>
    <head></head>
    <body>
        <?php
        foreach($c->items() as $item) {
            ?>
            <article>
                <img src="<?= $item->image('miniature',[400,100]) ?>" alt="miniature">
                <h1><?= $item->text('title') ?></h1>
                <p><?= $item->number('reading_time') ?></p>
                <a href="article.php?id=<?= $item->getId() ?>">Read full article</a>
            </article>
            <?php
        }
        ?>
    </body>
</html>
```

<a name="how_to_use_collection_item"/>

## Create a Candide Collection Item
1. Import Candide.php at the beginning of your webpage
2. Instancy a CandideCollectionItem in a variable called $c, it requires two parameters : the name of your collection and the item id
3. Open PHP balise when needed and call the type function where you need the content. If a type function is called in the collection and the collectionItem, it will be the same value.

```php
<?php
include 'admin/Candide.php';
$c = new CandideCollectionItem('your_collection_name_in_snake_case', $_GET['id']);
?>
<html>
    <head>
        <title><?= $c->text('title') ?></title>
    </head>
    <body>
        <h1><?= $c->text('title') ?></h1>
        <p><?= $c->number('reading_time') ?></p>
        <p><?= $c->text('content',true) ?></p>
        <img src="<?= $c->image('profil_picture',[200,200]) ?>" alt="Profil picture">
    </body>
</html>
```

<a name="how_to_use_multi_candide_instances"/>

## Use many Candide instances on a single page
Each Candide instance must be called $c but you can call multiple instances of Candide by creating an array called $c to keep them all.

```php
<?php 
include 'admin/Candide.php';
$c[0] = new CandidePage('header');
$c[1] = new CandidePage('home');
$c[2] = new CandidePage('footer');
?>
<html>
    <head></head>
    <body>
        <header>
            <h2><?= $c[0]->text('website_name') ?></h2>
            <p><?= $c[0]->text('catch_phrase') ?></p>
        </header>
        <main>
            <p><?= $c[1]->text('content',true) ?></p>
        </main>
        <footer>
            <img src="<?= $c[2]->image('footer_image',[1000,250]) ?>" alt="Footer banner" />
        </footer>
    </body>
</html>
```

<a name="how_to_use_types"/>

## Candide native supported types
Candide support the 3 main data types natively but it can be extended to any data types with plugins.

Each of this element getter functions can be called on a CandidePage or a CandideCollectionItem instance and also on a item iterated via the CandideCollection().items().

### Text element
It creates a textfield (WYSIWYG or not) on the admin side and return the text on the frontside of your website.

```php
<?php
/**
 * Generate a text field on admin platform
 * Return text on frontside
 *
 * @param String $title [Field title for admin interface]
 * @param Bool $wysiwyg [Option to enable a wysiwyg editor in admin interface]
 * @return String
 */
public function text(String $title, Bool $wysiwyg = false):String {...}
?>

/**
 *  Call text on a CandidePage, CandideCollectionItem, or one of a CandideCollection().items()
 */
<?= $c->text('name',false) ?>
```

### Image element
It creates an image field on the admin side and return the image url from root directory on the frontside of your website.

```php
<?php
/**
 * Generate an image field on admin platform
 * Return image url from root directory on front side
 *
 * @param String $title [Field title for admin interface]
 * @param Array $size [ [Width,Height] of your image, Candide automatically resize the input image]
 * @param Bool $crop [If you pass crop to false, the image will always fit the size you defined without cropping]
 * @return String
 */
public function image(String $title, Array $size, Bool $crop = true):String {...}
?>

/**
 *  Call text on a CandidePage, CandideCollectionItem, or one of a CandideCollection().items()
 */
<?= $c->image('banner',[1000,400],true) ?>
```

### Number element
It creates a number field on the admin side and return the number formatted according to a [NumberFormatter configuration](https://www.php.net/manual/en/class.numberformatter.php) on the frontside of your website.

```php
<?php
/**
 * Generate a number field on admin platform
 * Return formatted number on the front side
 *
 * @param String $title [Field title for admin interface]
 * @param Int $format [Echo formatting method]
 * @return String
 */
public function number(String $title, Int $format = NumberFormatter::DECIMAL):String {...}
?>

/**
 *  Call text on a CandidePage, CandideCollectionItem, or one of a CandideCollection().items()
 */
<?= $c->number('reading_duration', NumberFormatter::DECIMAL) ?>
```

<a name="configuration"/>

# Configure Candide
You can find all Candide Configuration files in /admin/config/.

## Global configuration : CandidConfig.php
`DEV_MODE` must be set to true to allow you to update the admin platform. Put it to false when you want to lock the administration interface and hide the update button.

`PROJECT_NAME` is the name of your project in snake_case. 

`LOCALE` must be set to the admin interface language you want. You can set `LOCALE` to any of the LCIS string listed on [this website](https://www.science.co.il/language/Locale-codes.php).

Edit `LOCALE` will automatically load administration side traduction if it exists on the [Candide project github folder dedicated to it](https://github.com/RomainPct/Candide_PHP_FlatFiles_CMS/tree/master/AdminTraductions). Else, just create the traduction for your language following traduction files which already exist and put it in admin/config/languages/. It will be really welcome if you share this traduction file with the whole Candide community.

## Customize Candide homepage
You can edit the administration side homepage into the customHome.php file avalaible in admin/config/ folder.

## Custom CSS on administration side
You can customize the administration side CSS thank's to the custom.css file avalaible in admin/config/ folder. 

<a name="autogeneration"/>

# How to generate the admin platform ?
Just go to the admin platform home and click on "Update the admin platform", it's done ! DEV_MODE has tu be set to true to has access to this button on the [Candide configuration file](#autogeneration_config).

You can debug the update if needed by opening the console and check "Keep history" then update the platform and you will be able to see the logs and detect a potential error in your code.

![Candide admin platform generation](https://github.com/RomainPct/Candide_PHP_FlatFiles_CMS/blob/master/.github/readme_generate_admin_platform.gif)

## Generate pages and collections manually with a JSON file
Candide also allows you to configure pages or collections with a structure JSON file. It's easy to configure : create a json file in the dedicated directory named YOUR_PAGE_NAME.json and directly type the fields you want in the json file. Then, just click the "Update admin platform" on Candide backoffice frontpage and it's done.

### Configure a page with a JSON file
Create a json file in the directory admin/config/structures/pages/ and call it "YOUR_PAGE_NAME.json". Then create your structure as following :
```json
{
    "FIELD_NAME": {
        "type": "text"
    },
    "description": {
        "type": "text",
        "options": [
            "wysiwyg": true
        ]
    },
    "cover": {
        "type": "image",
        "options": {
            "width": 1080,
            "height": 540,
            "crop": true
        }
    },
    "counter": {
        "type": "number",
        "options": {
            "format": 1
        }
    }
}
```
Then you just have to "Update the admin platform" to get this page in the admin platform.

### Configure a collection with a JSON file
Create a json file in the directory admin/config/structures/collections/ and call it "YOUR_COLLECTION_NAME.json".
- You can create many collections with the same structure by specifying the instances parameter in your json file.
- You can specify via general item the properties that you want to be able to access from CandideCollection and CandideCollectionItem
- You can specify via detailed item the properties that you want to be able to access only from CandideCollectionItem
Then create your structure as following :
```json
{
    "instances": [
        "coats",
        "caps"
    ],
    "generalItem": {
        "title": {
            "type": "text"
        },
        "date": {
            "type": "text"
        }
    },
    "detailedItem": {
        "description": {
            "type": "text",
            "options": {
                "wysiwyg": true
            }
        }
    }
}
```

### Use custom fields from plugins in a JSON configuration file
You are also able to use custom field imported via plugin in your JSON configuration files. Just specify the custom type and then add the options required by the custom field in the option array of the field + specify the "plugin" in the options.
```json
{
    "video": {
        "type": "youtube_video",
        "options": {
            "plugin": "sample_candide_class_plugin"
        }
    }
}
```

<a name="plugins"/>

# How to install a plugin ?

<a name="plugins_installation"/>

## Installation
It exists 3 main types of Candide plugins :
- Administration plateform visual extension plugin
- Backend actions plugin
- Candide class extension plugin

Each of them have specificities but to install them, you just have to put them in the admin/plugins/ folder and it's done.
Feel free to browse this [list of usefull Candide plugins](https://github.com/RomainPct/Candide_PHP_FlatFiles_CMS/blob/master/PluginsList.md).

<a name="plugins_visual_interface"/>

## Administration plateform visual extension plugin
This kind of plugin provide you an extension directly avalaible from the left panel of the administration platform. You can create a visual extension if you need to manage specific actions directly in the candide administration platform (Mailing list, statistics, ...).

<a name="plugins_backend_actions"/>

## Backend actions plugin
Backend plugins are extensions that are not avalaible visually but react to specific events as image upload, content saved... This kind of plugin can be interisting to do actions automatically in background (file compression, update verification...).

<a name="plugins_class_extension"/>

## Candide class extension plugin
Candide class extension plugin allow you to give new features to Candide classes. For exemple, you can create specific element type (more advanced than text, image or number) to speed-up your workflow and improve the administration interface (Youtube video element, Price element, Video element...).

To use this kind of plugin, you have to ask to the candide instances which need them to load them thank's to a last argument on the constructor.

```php
<?php
$c[0] = new CandidePage('page_name',['first_plugin_name','second_plugin_name']);
$c[1] = new CandideCollection('collection_name',['plugin_name']);
$c[2] = new CandideCollectionItem('collection_name',$_GET['id'],['plugin_name']);
?>
```

<a name="local_to_prod"/>

# From local to production
Thank's to its flatfiles architecture, Candide is really easy to take from a local server to a production server. You just have to copy the admin and the CandideData folder to the production server. Everything will now work as it was on the local server.

<a name="assistance"/>

# Assistance
If you need specific assistance with a Candide specialist, you can reach the founder of this project : Romain Penchenat at romain.penchenat@gmail.com

<a name="license"/>

### License

This project is licensed under the terms of the Apache 2.0 license.
This project was designed and developed by [Romain Penchenat](https://romainpenchenat.com)

Candide use two libraries :
- [Pell wysiwyg editor](https://github.com/jaredreich/pell) Copyright (c) Jared Reich
- [Slip.js](https://github.com/kornelski/slip) Copyright 2019 @kornelski