:navigation-title: Upgrade Notes

..  _upgrade:

=============
Upgrade Notes
=============

This document provides important information for upgrading the `plesk_widget`
extension to newer versions. Please read the relevant sections carefully before
performing an upgrade, especially if you are skipping multiple major versions.

General Upgrade Advice
======================

*   Always create a backup of your TYPO3 installation and database before upgrading.
*   Test upgrades on a development or staging environment first.
*   Clear all TYPO3 caches (frontend and backend) after an upgrade.
*   Run the "Upgrade Wizards" in the TYPO3 Install Tool if available.


..  _upgrade-configuration-migration:

Migration of Extension Configuration
====================================

With the upgrade from version 3.x to 4.0.0, the way the `plesk_widget`
extension is configured fundamentally changed.

Previously, global settings for Plesk server access (host, port, username,
password, and the domain for PHP settings) were managed through the
TYPO3 Install Tool (extension configuration). Additionally, the
`diskUsageType` (%, MB, or GB) was also a global setting.

These global settings have been removed in favor of a more flexible,
record-based configuration.

Breaking Changes
----------------

*   **Removed Global Extension Settings:** The global extension settings for
    Plesk server access (`host`, `port`, `username`, `password`, `domain`)
    and `diskUsageType` are no longer available.
*   **Plesk API Library Update:** The underlying `plesk/api-php-lib` dependency
    has been updated from version 1.1.2 to 2.2.1. This updated version is also
    directly included for Classic/ZIP package installations.

Migration Steps
---------------

1.  **Create Plesk Server Records:** You must now create dedicated "Plesk Server"
    records in the TYPO3 backend. These records (stored in the
    `tx_pleskwidget_server` table) can only be created on the root page (PID 0)
    and are therefore only manageable by a TYPO3 administrator. They will hold
    the `host`, `port`, `username`, `password`, and `domain` for each Plesk
    server you wish to monitor.
2.  **Configure Widgets Individually:** The `diskUsageType` setting is now
    configured directly within each individual Dashboard Widget. When adding
    or editing a Plesk widget, you will select the desired Plesk Server record
    and specify the disk usage display type.

**Important:** There is no automated upgrade wizard for this configuration
migration. Users of the extension must manually re-create their Plesk server
configurations as records and adjust their Dashboard Widgets accordingly.
