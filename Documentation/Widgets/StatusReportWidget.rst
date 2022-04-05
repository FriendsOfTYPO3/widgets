.. include:: /Includes.rst.txt

.. _status-report-widget:

====================
Status Report Widget
====================

This widget will give you an overview of all possible problems in your installation.
You can also click on the link to go to the reports module.

.. figure:: /Images/StatusReportWidget.png
   :class: with-shadow
   :alt: An example of the Status Report Widget

   This is how the Status Report widget could look like.

.. important::

   This widget is only available when you have
   `EXT:reports <https://extensions.typo3.org/extension/reports>`__
   installed and activated.


Options
-------

This widget has some options. To override the default options, see
":ref:`ext_dashboard:adjust-settings-of-widget`" in the dashboard documentation.

.. confval:: showErrors

   :type: bool
   :default: true

   Define if you want to show reports with the severity error in your widget.

.. confval:: showWarnings

   :type: bool
   :default: false

   Define if you want to show reports with the severity warning in your widget.
