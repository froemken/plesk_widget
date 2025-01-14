:navigation-title: FAQ

..  _faq:

================================
Frequently Asked Questions (FAQ)
================================

..  accordion::
    :name: faq

    ..  accordion-item:: Which Plesk versions are supported?
        :name: plesk-obsidian
        :header-level: 2

        I only have access to a Plesk Obsidian server. Can not say, if
        this extension will work on other plesk versions.


    ..  accordion-item:: I can not find any plesk widgets
        :name: missing-widgets
        :header-level: 2

        As a normal TYPO3 backend user it may happen that your administrator
        does not give you access rights to add these dashboard widgets. Please
        ask him to add the missing rights.

    ..  accordion-item:: Why a plain Plesk password is needed?
        :name: help
        :header-level: 2

        EXT:plesk_widget is working with the Plesk XML API. In user context
        there is no possibility to retrieve an access token. This is only
        possible as an Plesk administrator. Only he can create an access
        token for user context. Maybe I will find time to implement such a
        possibility in future. But I'm pretty sure most hosters wont generate
        an access token for you.
