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
    Latest Phalcon Framework extension enabled (>= 0.7.0)

### Installation

You need to have PhalconPHP installed on your web server as an extension. For
installation instructions, please follow this guide : http://phalconphp.com/documentation/install.
mod_rewrite must be enabled for IIS/Apache (or try_files for nginx). The mysql
extension must also be enabled.

Download or clone the application and upload it to your web server.

### Database Setup

    echo "CREATE DATABASE hhf CHARACTER SET utf8 COLLATE utf8_general_ci;" |mysql -u root -p

    echo "GRANT ALL PRIVILEGES ON hhf.* TO hhf_user@localhost IDENTIFIED BY '12345';" |mysql arrestify -u root -p

Please change the username and password on the line above to meet your requirements.

### Schema

You can import the schema using the following command:

    mysql -u root -p hhf < app/schema/schema.sql

Make sure the path of the schema file matches your setup.

The default username is "admin@hhf.ld" and the default password is "a" (without the quotes)

### Configuration

Edit the configuration file with your information.

    app/config/config.ini

### Apache configuration

Here is a sample configuration for Apache. Note that the DocumentRoot points to the public folder

    <VirtualHost *:80>
            ServerAdmin webmaster@localhost
            ServerName hhf.ld

            DocumentRoot /home/www/hhf.ld/public
            <Directory /home/www/hhf.ld>
                    Options -Indexes FollowSymLinks
                    AllowOverride All
                    Order allow,deny
                    Allow from all
            </Directory>
    </VirtualHost>



