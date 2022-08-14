# Simple chat application
## Requirements
You need to install these dependencies below on your machine.
- GIT
- PHP 7.4
- MySQL
- Apache server (Xampp)

## Deploy
To deploy this application you need to have a domain name or create a virtual host on your local machine.

## How to run this application on local machine
### 1. Allow the usage of custom virtual hosts
By default, xampp in ubuntu won't use the httpd-vhosts.conf file (the location of the virtual hosts), therefore we need to indicate that this file will be included during the runtime of apache. Open with your favorite code editor the httpd.conf file located tipically in /opt/lampp/etc or just execute the following command in your terminal to open a simple editor:
```bash
$ sudo gedit /opt/lampp/etc/httpd.conf
```
Now locate yourself in (about) the line 487 where you probably will find the following lines:
```bash
# Virtual hosts
#Include etc/extra/httpd-vhosts.conf
```
As you can see, the Include statement that includes the httpd-vhosts.conf file is commented.
Proceed to modify the line uncommenting that line:
```bash
# Virtual hosts
Include etc/extra/httpd-vhosts.conf
```
### 2. Create a custom domain in the hosts file of your system
To start, edit the hosts file located in /etc using your favorite code editor, or just by executing the following
command in the terminal:
```bash
# Virtual hosts
sudo gedit /etc/hosts
```
add your custom host, So finally, our hosts file will look like:
```bash
127.0.0.1	localhost
# new added line
127.0.0.1	tchat.wip
```
### 3. Create your first virtual host
Typically, you need to create the virtual host in the httpd-vhosts.conf file located in /opt/lampp/etc/extra. Use your favorite editor to edit that file or just execute the following command to edit it in a terminal:
```bash
sudo gedit /opt/lampp/etc/extra/httpd-vhosts.conf
```
And create your own virtual host in this file
```bash
<VirtualHost *:80>
    DocumentRoot "/opt/lampp/htdocs/tchat"
    ServerName tchat.wip
</VirtualHost>
```
The deep and custom configuration of your VirtualHost is up to you. Save the file.

## How To Install

```bash
# Clone this repository
$ git clone git@github.com:imranouadid/tchat.git

# Copy the cloned directory to apache server Ex: opt/lampp/htdocs/
$ cp tchat/ -R /opt/lampp/htdocs/

# Go to config folder and find a file called config.php
$ gedit /opt/lampp/htdocs/tchat/app/config/config.php
## modify configuration of database and domain name

    ## config database
    define('DB_HOST','localhost');
    define('DB_USER','imran');
    define('DB_PASS','97900xmen');
    define('DB_NAME','tchat');

    define('APP_ROOT',dirname(dirname(__FILE__)));
    
    ## config domain name
    define('URL_ROOT','http://tchat.wip');
    define('SITE_NAME','TCHAT');
    define('APP_VERSION','1.0.0');

```
Then you should import database file to your database server, check out this path to find database file: tchat/DB/tchat.sql
Go to your browser and type http://tchat.wip/. That's all.