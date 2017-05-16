Best Practice
-------------


Using your own templates
------------------------

You can add additional paths via **TypoScript Setup**.
You should never edit the original templates of an extension as those changes will vanish if you upgrade the extension.
As any extbase based extension, you can find the templates in the directory ``Resources/Private/``.If you want to overwrite tempalte files you can copy desire files to directory  where you store the templates like fileadmin folder or else where (see values with "1" below).
"0" is defined as fallback folder per default for the non-existing files in your defined folder:

.. code-block:: text

	plugin.tx_newscomment_newscomment {
		view {
			templateRootPaths {
				0 = EXT:tx_newscomment_newscomment/Resources/Private/Templates/
				1 = fileadmin/templates/newscomment/Templates/
			}
			partialRootPaths {
				0 = EXT:tx_newscomment_newscomment/Resources/Private/Partials/
				1 = fileadmin/templates/newscomment/Partials/
			}
			layoutRootPaths {
				0 = EXT:tx_newscomment_newscomment/Resources/Private/Layouts/
				1 = fileadmin/templates/newscomment/Layouts/
			}
		}
	}


Because constants are used for .1 in setup per default, you can also use **TypoScript Constants** like:

.. code-block:: text

	plugin.tx_newscomment_newscomment.view {
		templateRootPath = fileadmin/templates/newscomment/Templates/
		partialRootPath = fileadmin/templates/newscomment/Partials/
		layoutRootPath = fileadmin/templates/newscomment/Layouts/
	}

Overwrite Labels and Validation messages
----------------------------------------

You can overwrite any label of news comment extention via TypoScript Setup.
Have a look into locallang.xlf (EXT:news_comment/Resources/Private/Language/locallang.xlf) for getting the relevant keys,
that you want to overwrite (e.g. thanksrating_msg).

.. code-block:: text

	plugin.tx_newscomment_newscomment {
		_LOCAL_LANG.default.pleaselogin_msg = Please login to rate this comment!
		_LOCAL_LANG.de.notallowrating_msg = You may not allow rating multiple times!
	}

Settings available to include JQuery
------------------------------------

Files are located at ``Resources/Public/jquery``

**Versions available are:**

- jquery-1.11.3.min.js
- jquery-2.1.4.min.js
- jquery-2.2.3.min.js


.. code-block:: text

	plugin.tx_newscomment_newscomment.settings {
		jQuery{
			require = 1
			version = jquery-2.2.3.min.js
			}
	}

Realurl Settings
----------------

This is just sample code you can use for real url settings of the extensions news and news_comment

.. code-block:: text

//News Detail Page
'fixedPostVars' => array(
	'22'=> array(
			array(
					'GETvar' => 'tx_news_pi1[controller]',
					'noMatch' => 'bypass',
			),
			array(
					'GETvar' => 'tx_news_pi1[action]',
					'noMatch' => 'bypass',
			),
			array(
					'GETvar' => 'tx_news_pi1[news]',
					'lookUpTable' => array(
							'table' => 'tx_news_domain_model_news',
							'id_field' => 'uid',
							'alias_field' => 'title',
							'addWhereClause' => ' AND deleted !=1 AND hidden !=1',
							'enable404forInvalidAlias' => 1,
							'useUniqueCache' => 1,
							'useUniqueCache_conf' => array(
									'strtolower' => 1,
									'spaceCharacter' => '-',
							),
					),
			),
			//cHash
			array(
					'GETvar' => 'cHash',
					'noMatch' => 'bypass',
			),                 
	),
),

//news_comment extension
'postVarSets' => array(
	'_DEFAULT' => array(
			//News Comments
			'news-comment' => array(
					array(
							'GETvar' => 'tx_newscomment_newscomment[controller]',
					),
					array(
							'GETvar' => 'tx_newscomment_newscomment[action]',
					),
			),
			'searchterm' => array(
					array(
							'GETvar' => 'filter[searchterm]',
					),
			),
			'sort' => array(
					array(
							'GETvar' => 'filter[sort]',
					),
			),
			'page' => array(
					array(
							'GETvar' => 'tx_newscomment_newscomment[@widget_0][currentPage]',
					),
			),
	),
),