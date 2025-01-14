:navigation-title: Installation

..  _installation:

============
Installation
============

..  _installation-composer:

Install with Composer
=====================

If your TYPO3 installation works in composer mode, please execute following
command:

..  code-block:: bash

    composer req stefanfroemken/plesk-widget
    vendor/bin/typo3 extension:setup --extension=plesk_widget

If you work with DDEV please execute this command:

..  code-block:: bash

    ddev composer req stefanfroemken/plesk-widget
    ddev exec vendor/bin/typo3 extension:setup --extension=plesk_widget

See also `Installing extensions, TYPO3 Getting started <https://docs.typo3.org/permalink/t3start:installing-extensions>`_.

..  _installation-classic:

Install in Classic Mode
=======================

On non composer based TYPO3 installations you can install `plesk_widget` still
over the ExtensionManager:

..  rst-class:: bignums

1.  Login

    Login to backend of your TYPO3 installation as an administrator or system
    maintainer.

2.  Open ExtensionManager

    Click on `Extensions` from the left menu to open the ExtensionManager.

3.  Update Extensions

    Choose `Get Extensions` from the upper selectbox and click on
    the `Update now` button at the upper right.

4.  Install `plesk_widget`

    Use the search field to find `plesk_widget`. Choose the `plesk_widget` line from
    the search result and click on the cloud icon to install `plesk_widget`.

..  _installation-next-step:

Next step
=========

:ref:`Configure plesk_widget <configuration>`.
