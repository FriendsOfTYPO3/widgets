.. include:: /Includes.rst.txt
.. highlight:: bash

.. _installation:

============
Installation
============

Target group: **Administrators**

Installation with composer
==========================

Check whether you are already using the extension with::

   composer show | grep widgets

This should either give you no result or something similar to:::

   friendsoftypo3/widgets v1.0.0 Dashboard Widgets for TYPO3.

If it is not yet installed, use the ``composer require`` command to install the extension::

   composer require friendsoftypo3/widgets


Now head over to the extension manager and activate the extension.

Installation without composer
=============================

You can simply install stable versions of EXT:widgets using the Extension Manager
from the TYPO3 backend.

#. Go to the **Extension Manager**, select **Get Extensions** and search for
   "widgets".
#. Install the extension.
#. Activate the extension.
#. Have fun!
