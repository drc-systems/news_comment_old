Configuration
-------------

There are various typoscript settings available to improve the usability of extension.

Properties
^^^^^^^^^^

.. container:: ts-properties

	============================================== ======================================= ============== ===============
	Property                                       Title                                   Sheet          Type
	============================================== ======================================= ============== ===============
	requireCommentApproval_                         Require comment approval               General         int
	addJsValidations_                               JQuery validations                     General         int
	showPagination_                                 Show Pagination                        General         int
	recordPerPage_                                  Records per page                       General         int
	showSearch_                                     Show Search                            General         int
	showSortBy_                                     Show sorting combo                     General         int
	enableRating_                                   Enable Rating                          General         int
	enableRatingAtLogin_                            Enable Rating at login                 General         int
	showTotalRatings_                               Show Total Rating                      General         int
	badwordFilterString_                            Bad Filtering                          General         int
	dateformat_                                     Date Format                            General         int
	notification.newsauthor.sendMailToNewsAuthor_   Send mail to News Author               General         int
	`notification.newsauthor.authorMailSubject`_    Author email subject                   General         string
	`notification.siteadmin.sendMailToAdmin`_       Send mail to Site Admin                General         int
	`notification.siteadmin.adminEmail`_            Site Admin mail address                General         string
	`notification.siteadmin.adminMailSubject`_      Admin email subject                    General         string
	============================================== ======================================= ============== ===============


.. _requireCommentApproval:

requireCommentApproval
""""""""""""""""""""""
.. container:: table-row

   Property
         requireCommentApproval
   Data type
         int
   Description
         Define to require comment approval or not. The value 1 indicates required comment approval. It will show, comment on the list page after it will be made visible by the site admin.

.. _addJsValidations:

addJsValidations
""""""""""""""""
.. container:: table-row

   Property
         addJsValidations
   Data type
         int
   Description
         Define the jQuery validations require or not. Value 1 stands for it include the jQuery validations, and 0 value exclude jQuery validations and raise serverside validations.


.. _showPagination:

showPagination
""""""""""""""
.. container:: table-row

   Property
         showPagination
   Data type
         int
   Description
         Define the show/hide pagination while listing of comments.


.. _recordPerPage:

recordPerPage
"""""""""""""
.. container:: table-row

   Property
         recordPerPage
   Data type
         int
   Description
         Define the number of comments pers page if you enabled paginations.


.. _showSearch:

showSearch
""""""""""
.. container:: table-row

   Property
         showSearch
   Data type
         int
   Description
         Define the show/hide search box on comment listing page.The search box will search the particular comment(s) from the list by matching word string.


.. _showSortBy:

showSortBy
""""""""""
.. container:: table-row

   Property
         showSortBy
   Data type
         int
   Description
         Define the sorting combo will display on list page or not. It will allows to sort comments by oldest record first, newest record first, best rated, worst rated.


.. _enableRating:

enableRating
""""""""""""
.. container:: table-row

   Property
         enableRating
   Data type
         int
   Description
         Define to show/hide rating on comment list page.


.. _enableRatingAtLogin:

enableRatingAtLogin
"""""""""""""""""""
.. container:: table-row

   Property
         enableRatingAtLogin
   Data type
         int
   Description
         It allows user to rate comments only after at login.


.. _showTotalRatings:

showTotalRatings
""""""""""""""""
.. container:: table-row

   Property
         showTotalRatings
   Data type
         int
   Description
         Define to show/hide total rating at bottom of list comment page.

.. _badwordFilterString:

badwordFilterString
"""""""""""""""""""
.. container:: table-row

   Property
         badwordFilterString
   Data type
         string
   Description
         It does not allow to post comment with bad words.The value contains comma separated strings.

.. _dateformat:

dateformat
""""""""""
.. container:: table-row

   Property
         dateformat
   Data type
         string
   Description
         Defines the date format. The value contains valid php date formates.Default value is F j, Y, g:i a


.. _notification.newsauthor.sendMailToNewsAuthor:

notification.newsauthor.sendMailToNewsAuthor
""""""""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         notification.newsauthor.sendMailToNewsAuthor
   Data type
         string
   Description
         Define to send notification mail to news author.

.. _notification.newsauthor.authorMailSubject:

notification.newsauthor.authorMailSubject
"""""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         notification.newsauthor.authorMailSubject
   Data type
         string
   Description
         Define email subject of news author mail.

.. _notification.siteadmin.sendMailToAdmin:

notification.siteadmin.sendMailToAdmin
""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         notification.siteadmin.sendMailToAdmin
   Data type
         string
   Description
         Define to send notification mail to site admin.

.. _notification.siteadmin.adminEmail:

notification.siteadmin.adminEmail
"""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         notification.siteadmin.adminEmail
   Data type
         string
   Description
         Define the site admin mail address.

.. _notification.siteadmin.adminMailSubject:

notification.siteadmin.adminMailSubject
"""""""""""""""""""""""""""""""""""""""
.. container:: table-row

   Property
         notification.siteadmin.adminMailSubject
   Data type
         string
   Description
         Define the mail subject of admin email.



.. hint::
           You can configure these settings by the constant editor.


.. figure:: ../Images/AdministratorManual/configurationSettings.png
	:width: 1000px
	:alt: Configration Settings

