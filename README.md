phalcon-angular-harryhogfootball
================================

Application written with PhalconPHP (http://phalconphp.com) and AngularJS
(http://angularjs.org) to record and display the Kicks and Game balls of
Harry Hog Football (http://harryhogfootball.com), the ultimate podcast for
Redskins Fans.

Installation

You need to have PhalconPHP installed on your web server as an extension. For
installation instructions, please follow this guide : http://phalconphp.com/documentation/install.
mod_rewrite must be enabled for IIS/Apache (or try_files for nginx). The mysql
extension must also be enabled.

Download or clone the application and upload it to your web server.

Create a new database as follows:

<db instructions>

Edit app/config/config.ini with the username/password/database you chose.

Run the migrations using

phalcon run-migration

Alternatively you can import the schema.sql file, located in the schema folder

<need to add the schema file>