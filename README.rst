secure-hosts-importer
#####################

.. contents:: :local:


What does it do?
****************

This php script allows you to import hosts file from any url and mix it with your hosts file.
For security reason each line of imported hosts is checked to have "0.0.0.0" at the beginning so its impossible for
bad guys to fake your local host file with wrong domain pointers.


Installation
************

Clone repo or download file ``secure-hosts-importer.php``.


Usage
*****

1. If you want to inject downloaded hosts into specific place of your hosts file then use markers ###HOSTS_IMPORTER_START###,
   ###HOSTS_IMPORTER_END###. If you do not have those markers then they will be created automatically at the end of file.

2. Run ``sudo php secure-hosts-importer.php``. It will import hosts from this project https://github.com/StevenBlack/hosts from
   url https://raw.githubusercontent.com/StevenBlack/hosts/master/alternates/fakenews-gambling-porn-social/hosts

   You can change it to your url by setting env var ``HOSTS_IMPORTER__HOSTS_FILE_URL``.

3. Check your hosts file. Between markers there should be your hosts from url injected. The old hosts file is backuped
   under name ``hosts.[date].secure-hosts-importer.backup``. After few updates you can expect to see something like below.
   There is rotation set to keep last 5 backups.

   ::

     hosts
     hosts.20180628103341.secure-hosts-importer.backup
     hosts.20180628103435.secure-hosts-importer.backup
     hosts.20180628103445.secure-hosts-importer.backup
     hosts.20180628103528.secure-hosts-importer.backup
     hosts.20180628103537.secure-hosts-importer.backup

4. By default script look for hosts file under ``/etc/hosts``. If you have your hosts file under different location then you
   can overwrite it with env var ``HOSTS_IMPORTER__HOSTS_FILE``

Local server
############

If you use local Apache server then by default it will listen to all IPs. When apache does not find requested domain for
any vhost it takes first vhost sorted alphabetically. This is why its good to create vhost which will catch all traffic
from 0.0.0.0. Its suggested that you create new vhost in folder of vhosts and name it "00-default.conf" and put following
config inside:

::

  <VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot "/var/www/empty"
    <Directory "/var/www/empty">
        AllowOverride None
        Options -Indexes
        Require all denied
    </Directory>
    ErrorLog /dev/null
    CustomLog /dev/null comm
  </VirtualHost>

  <VirtualHost *:443>
    ServerAdmin webmaster@localhost
    DocumentRoot "/var/www/empty"
    <Directory "/var/www/empty">
        AllowOverride None
        Options -Indexes
        Require all denied
    </Directory>
    ErrorLog /dev/null
    CustomLog /dev/null comm
  </VirtualHost>

Changelog
*********

See https://github.com/sourcebroker/secure-hosts-importer/blob/master/CHANGELOG.rst
