secure-hosts-importer
#####################

  .. image:: https://poser.pugx.org/sourcebroker/secure-hosts-importer/v/stable
    :target: https://packagist.org/packages/sourcebroker/secure-hosts-importer

  .. image:: https://poser.pugx.org/sourcebroker/secure-hosts-importer/license
    :target: https://packagist.org/packages/sourcebroker/secure-hosts-importer

.. contents:: :local:


What does it do?
****************

This package allows you to import hosts file from any url and mix it with your hosts file.
For security reason each line of imported hosts is checked to have "0.0.0.0" at the beginning so its impossible for
bad guys to fake your local host file with wrong domain pointers.


Installation
************

Clone repo or download file ``secure-hosts-importer.php``.


Usage
*****

1. Edit your hosts file and put two markers in the place you want the imported hosts to be injected. By default the
   markers are ###HOSTS_IMPORTER_START###, ###HOSTS_IMPORTER_END###. Each marker must be in separate line.

   Example. By default on mac hosts file is place on ``/etc/hosts`` and looks like:

   ::

      # Host Database
      #
      # localhost is used to configure the loopback interface
      # when the system is booting.  Do not change this entry.
      ##
      127.0.0.1       localhost
      255.255.255.255 broadcasthost
      ::1             localhost



   So with markers it will look like:
   ::

      # Host Database
      #
      # localhost is used to configure the loopback interface
      # when the system is booting.  Do not change this entry.
      ##
      127.0.0.1       localhost
      255.255.255.255 broadcasthost
      ::1             localhost

      ###HOSTS_IMPORTER_START###
      ###HOSTS_IMPORTER_END###

      # Here can be other lines of your config


2. Run ``sudo php secure-hosts-importer.php``. It will import hosts from this project https://github.com/StevenBlack/hosts from
   url https://raw.githubusercontent.com/StevenBlack/hosts/master/alternates/fakenews-gambling-porn-social/hosts

   You can change it to your url by setting env var ``HOSTS_IMPORTER__HOSTS_FILE_URL``.

3. Check your hosts file. Between markers there should be your hosts from url injected. The old hosts file is backuped
   under name ``hosts.[date].backup``. After few updates you can expect to see something like below. There is rotation
   set to keep last 5 backups.

   ::

     hosts
     hosts.20180628103341.backup
     hosts.20180628103435.backup
     hosts.20180628103445.backup
     hosts.20180628103528.backup
     hosts.20180628103537.backup

4. By default script look for hosts file under ``/etc/hosts``. If you have your hosts file under different location then you
   can overwrite it with env var ``HOSTS_IMPORTER__HOSTS_FILE``

Changelog
*********

See https://github.com/sourcebroker/secure-hosts-importer/blob/master/CHANGELOG.rst
