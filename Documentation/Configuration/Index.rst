..  include:: /Includes.rst.txt


..  _configuration:

=============
Configuration
=============

Extension Settings Reference
============================

Jump over to the BE module "Settings", type in your InstallTool
password and click on "Configure extensions". Than choose
"plesk_widget".


..  _host:

host
----

Example: example.com

Enter the host, where this extension can access the plesk system.
In most cases your domain name (without scheme, path and port).


..  _port:

port
----

Default: 8443

Which port should this extension use to access the plesk system?


..  _username:

username
--------

Example: max.mustermann

Set the username of the customer account of plesk.


..  _password:

password
--------

Example: doNotUseThis

Set the password to access the customer account of plesk.

..  _diskUsageType:

diskUsageType
-------------

Example: %

You have the possibility to show the disk usage in
percent, MegaByte or in GigaByte.


..  _domain:

domain
------

Example: 124.example.com

Without a domain the Plesk API will show information about the first retrieved
domain. To show the information about a specific domain you should set this
value to a domain name in your plesk hosting.
