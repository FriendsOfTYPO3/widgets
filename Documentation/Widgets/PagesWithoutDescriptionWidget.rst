.. include:: /Includes.rst.txt

.. _pages-without-description-widget:

=====================================
Pages without Meta Description Widget
=====================================

If you want to optimise your pages for search engines, it is quite important
to write unique and informative meta descriptions. Having no meta description
is most of the times giving you unexpected results in search engines.

This widget will show you the last edited pages without a meta description
specified. You can click on the pencil icon behind every page to directly edit
the page properties of the specific page.

.. figure:: /Images/PagesWithoutDescriptionWidget.png
   :class: with-shadow
   :alt: This widget is showing the last edited pages without a meta description

   This widget is showing the last edited pages without a meta description

.. important::

   This widget is only available when you have **EXT:seo** installed and activated


Options
-------
This widget has some options. To override the default options, see
:ref:`ext_dashboard:adjust-settings-of-widget`.


.. option:: $excludedDoktypes

   Some doktypes are not real pages and can be excluded from this overview.

   **Default:** 3, 4, 6, 7, 199, 254, 255

.. option:: $limit

   The number of pages without a meta description to show within the widget.

   **Default:** 8

An example:

:file:`Configuration/Services.yaml`::

  FriendsOfTYPO3\Widgets\Widgets\Provider\PagesWithoutDescriptionDataProvider:
    arguments:
      $excludedDoktypes: [3, 4, 6, 7, 199, 254, 255]
      $limit: 8
