:navigation-title: Introduction

..  _introduction:

============
Introduction
============

..  _what-it-does:

What does it do? And for whom?
==============================

This extension provides useful widgets for the TYPO3 Dashboard, offering a
quick overview of various information from your Plesk system.

It's designed for administrators, developers, and TYPO3 integrators who manage
Plesk-hosted websites. It can also serve as a monitoring tool for Plesk
parameters, even if the TYPO3 installation itself is not hosted on
a Plesk server.

The extension delivers some widgets for TYPO3 DashBoard to show various
information from your website which is hosted on a plesk system.

*   Show current disk usage (%, MB or GB)
*   Show owner
*   Show some PHP information
*   Show internal/external IP addresses
*   Button to Plesk (auto-login). On failure, button will link to plesk
    login screen. After successful authorization with the configured username
    and password, the extension can generate an auto-login link via the
    Plesk API, allowing the user to be directly logged into their
    Plesk panel in the browser.

..  _screenshots:

Screenshots
===========

..  figure:: /Images/PleskWebspace.png
    :alt: DashBoard Widget showing free and used webspace
    :class: with-shadow

    This widget shows a doughnut chart about your free and used webspace.

..  figure:: /Images/PleskServerInformation.png
    :alt: DashBoard Widget showing server information
    :class: with-shadow

    This widget shows the registered user, some ip addresses, and a button
    with a direct login to your plesk customer panel. On error, this button
    will redirect you to the plesk login form.

..  figure:: /Images/PleskPhpSettings.png
    :alt: DashBoard Widget showing PHP settings of a specific domain
    :class: with-shadow

    This widget will only work, if you have configured a "domain" in extension
    settings. If set, you will see some interesting PHP settings of the
    selected domain.
