# Candide - Lightweight PHP CMS for webdevelopers
Candide generate automaticaly an admin platform according to your php code. It's lightweight, easily extensible with plugins, and it uses a flatfile architecture therefore you don't need any database.

![Candide PHP Flatfiles banner](https://github.com/RomainPct/Candide_PHP_FlatFiles_CMS/blob/master/.github/readme_banner.png)

## Table of Contents  
1. [What's Candide ?](#what_is_candide)  
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [How to use Candide ?](#how_to_use)
    1. [Candide native types](#how_to_use_types)
        1.[Text](#how_to_use_types_text)
        2.[Image](#how_to_use_types_image)
        3.[Number](#how_to_use_types_number)
    2. [Create a Candide Page](#how_to_use_page)
    3. [Create a Candide Collection](#how_to_use_collection)
    4. [Create a Candide Collection Item](#how_to_use_collection_item)
    5. [Multi Candide instances on a single page](#how_to_use_multi_candide_instances)
5. [Admin platform autogeneration](#autogeneration)
    1. [Configuration](#autogeneration_config)
    2. [Generation](#autogeneration_generate)
6. [Work with plugins](#plugins)
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

<a name="how_to_use_types"/>

## Candide native supported types

<a name="how_to_use_types_text"/>

### Text

<a name="how_to_use_types_image"/>

### Image

<a name="how_to_use_types_number"/>

### Number

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
        <title><?php $c->text('title') ?></title>
    </head>
    <body>
        <img src="<?php $c->image('banner',[1080,200]) ?>" alt="Website banner">
        <h1><?php $c->text('title') ?></h1>
        <p><?php $c->text('content',true) ?></p>
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
                <img src="<?php $item->image('miniature',[400,100]) ?>" alt="miniature">
                <h1><?php $item->text('title') ?></h1>
                <p><?php $item->number('reading_time') ?></p>
                <a href="article.php?id=<?php $item->id() ?>">Read full article</a>
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
        <title><?php $c->text('title') ?></title>
    </head>
    <body>
        <h1><?php $c->text('title') ?></h1>
        <p><?php $c->number('reading_time') ?></p>
        <p><?php $c->text('content',true) ?></p>
        <img src="<?php $c->image('profil_picture',[200,200]) ?>" alt="Profil picture">
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
            <h2><?php $c[0]->text('website_name') ?></h2>
            <p><?php $c[0]->text('catch_phrase') ?></p>
        </header>
        <main>
            <p><?php $c[1]->text('content',true) ?></p>
        </main>
        <footer>
            <img src="<?php $c[2]->image('footer_image',[1000,250]) ?>" alt="Footer banner" />
        </footer>
    </body>
</html>
```

<a name="autogeneration"/>

# Admin platform autogeneration

<a name="autogeneration_config"/>

## Configure Candide

<a name="autogeneration_generate"/>

## How to generate the admin platform ?
[GIF Generation plateforme]
![Candide admin platform generation](https://github.com/RomainPct/Candide_PHP_FlatFiles_CMS/blob/master/.github/readme_generate_admin_platform.gif)

<a name="plugins"/>

# How to install a plugin ?

<a name="plugins_installation"/>

## Installation
Installer un plugin (les 3 types)
    Lien Liste des plugins référencés
    Installation

<a name="plugins_visual_interface"/>

## Type 1

<a name="plugins_backend_actions"/>

## Type 3

<a name="plugins_class_extension"/>

## Type 3

<a name="local_to_prod"/>

# From local to production
Mise en production

<a name="assistance"/>

# Assistance
Séance privé

<a name="license"/>

### License

Pell wysiwyg editor https://github.com/jaredreich/pell
Slip.js https://github.com/kornelski/slip

This project is licensed under the terms of the Apache 2.0 license.
This project was designed and developed by [Romain Penchenat](https://romainpenchenat.com)