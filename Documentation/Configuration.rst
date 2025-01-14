:navigation-title: Configuration

..  _configuration:

=============
Configuration
=============

Extension Settings Reference
============================

Jump over to the BE module :guilabel:`Settings`, type in your InstallTool
password and click on :guilabel:`Configure extensions`. Than choose
**plesk_widget**.

..  confval-menu::
    :name: input
    :display: table
    :type:

..  _host:

..  confval:: host
    :name: configuration-host
    :type: string
    :Required: true

    Enter the host, where this extension can access the plesk system.
    Plesk check mails of your hoster to find this domain name. If not known
    you may try your website domain name (without scheme, path and port).

    **Example**: plesk.example.com

..  confval:: port
    :name: configuration-port
    :type: int
    :default: 8443
    :Required: true

    Enter the port of your plesk domain (see domain above).

    **Example**: 8083

..  confval:: username
    :name: configuration-username
    :type: string
    :Required: true

    Set the username of your customer account on the plesk system.

    **Example**: max.mustermann

..  confval:: password
    :name: configuration-password
    :type: string
    :Required: true

    Set the password of your customer account on the plesk system.

    **Example**: Password%1234

..  confval:: diskUsageType
    :name: configuration-diskUsageType
    :type: string
    :Default: %
    :Required: true

    You have the possibility to show the disk usage in percent (%),
    MegaByte (MB) or in GigaByte (GB).

    **Example**: MB

..  confval:: domain
    :name: configuration-domain
    :type: string
    :Required: false

    EXT:plesk_widget comes with a dashboard widget where you can view PHP
    settings of one of your registered domains at plesk server. If you
    make use of it, you have to set this value to the exact domain name as it
    is registered in your customer account of the plesk server.

    **Example**: 124.example.com
