:navigation-title: Configuration

..  _configuration:

=============
Configuration
=============

Configuring Plesk Server Access
===============================

The `plesk_widget` extension uses dedicated records to store Plesk server
access information. This allows you to configure multiple Plesk servers and
select them individually for your dashboard widgets.

To configure a Plesk server:

1.  Navigate to the TYPO3 backend module :guilabel:`Records` to manage your
    records.
    These records (type `Plesk Server`) can only be created on the root page
    (PID 0) and are therefore only manageable by a TYPO3 administrator.
2.  Create a new record of type :guilabel:`Plesk Server`.
3.  Fill in the following fields:

    ..  confval:: title
        :name: configuration-title
        :type: string
        :Required: true

        A descriptive title for this Plesk server configuration. This title
        will be displayed when selecting the server in dashboard widgets.

        **Example**: My Main Plesk Server

    ..  confval:: host
        :name: configuration-host
        :type: string
        :Required: true

        Enter the hostname or IP address where this extension can access the Plesk system.
        If not known, you may try your website domain name (without scheme, path, and port).

        **Example**: plesk.example.com or 192.168.1.100

    ..  confval:: port
        :name: configuration-port
        :type: int
        :default: 8443
        :Required: true

        Enter the port of your Plesk system.

        **Example**: 8443

    ..  confval:: username
        :name: configuration-username
        :type: string
        :Required: true

        Set the username of your customer account on the Plesk system.

        **Example**: max.mustermann

    ..  confval:: password
        :name: configuration-password
        :type: string
        :Required: true

        Set the password of your customer account on the Plesk system. This password
        is stored encrypted.

    ..  confval:: domain
        :name: configuration-domain
        :type: string
        :Required: false

        If you want to use the dashboard widget that displays PHP settings, you must
        set this value to the exact domain name as it is registered in your customer
        account of the Plesk server.

        **Example**: 124.example.com

Configuring Dashboard Widgets
=============================

When adding or editing a `plesk_widget` dashboard widget, you will be prompted
to select one of your configured Plesk Server records.

Additionally, for widgets displaying disk usage, you can now specify the
display format directly within the widget's settings:

*   **Disk Usage Type:** Choose whether to display disk usage in percent (%),
    MegaByte (MB), or GigaByte (GB). This setting is specific to each widget.

    **Example**: MB
