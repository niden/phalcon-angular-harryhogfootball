phalcon-angular-harryhogfootball
================================

Application written with PhalconPHP (http://phalconphp.com) and AngularJS
(http://angularjs.org) to record and display the Kicks and Game balls of
Harry Hog Football (http://harryhogfootball.com), the ultimate podcast for
Redskins Fans.

### Requirements

To run this application on your machine, you need at least:

    PHP >= 5.3.6
    Apache Web Server with mod rewrite enabled
    Latest Phalcon Framework extension enabled (0.4.x)

### Installation

You need to have PhalconPHP installed on your web server as an extension. For
installation instructions, please follow this guide : http://phalconphp.com/documentation/install.
mod_rewrite must be enabled for IIS/Apache (or try_files for nginx). The mysql
extension must also be enabled.

Download or clone the application and upload it to your web server.

### Configuration

Check your database configuration and website's base URI.

    app/config/config.php


Create a new database and initialize the schema:

    php -r '
    require "app/config/config.php";

    $n = $config->database->name;
    $u = $config->database->username;
    $p = $config->database->password;

    echo `echo "CREATE DATABASE {$n}" | mysql -u {$u} -p {$p}`;
    echo `cat app/schema/schema.sql | mysql -u {$u} -p {$p} {$n}`;
    '
