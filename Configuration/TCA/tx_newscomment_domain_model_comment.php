<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment',
		'label' => 'description',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'description,newsuid,parentcomment,username,usermail,usersession,rate,user,rating,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('news_comment') . 'Resources/Public/Icons/tx_newscomment_domain_model_comment.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, newsuid, username, usermail, usersession, description, rate, user, rating, parentcomment, spam',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, username, usermail, usersession, description, website, rate, user, rating, parentcomment, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
	
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_newscomment_domain_model_comment',
				'foreign_table_where' => 'AND tx_newscomment_domain_model_comment.pid=###CURRENT_PID### AND tx_newscomment_domain_model_comment.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),

		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
	
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),

		'newsuid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment.newsuid',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'parentcomment' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment.parentcomment',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'user' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment.user',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'fe_users',
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'username' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment.username',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'usermail' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment.usermail',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'usersession' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment.usersession',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'description' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment.description',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			)
		),
		'rate' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment.rate',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'double2'
			)
		),
		
		'rating' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment.rating',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_newscomment_domain_model_rating',
				'foreign_field' => 'comment',
				'maxitems' => 9999,
				'behaviour' => array(
					'enableCascadingDelete' => TRUE
				),
				'appearance' => array(
					'collapseAll' => TRUE,
					'newRecordLinkPosition' => 'none',
					'levelLinksPosition' => 'none',
					'useSortable' => FALSE,
					'enabledControls' => array(
						'new' => FALSE,
						'dragdrop' => FALSE,
						'sort' => FALSE,
						'hide' => FALSE,
						'delete' => FALSE
					)
				)
			),

		),
		'crdate' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment.crdate',
			'config' => array(
				'type' => 'input'
			)
		),
		
		'parentcomment' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment.parentcomment',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_newscomment_domain_model_comment',
				'foreign_field' => 'comment',
				'maxitems' => 9999,
				'appearance' => array(
					'collapseAll' => TRUE,
					'newRecordLinkPosition' => 'none',
					'levelLinksPosition' => 'none',
					'useSortable' => FALSE,
					'enabledControls' => array(
						'new' => FALSE,
						'dragdrop' => FALSE,
						'sort' => FALSE,
						'hide' => FALSE,
						'delete' => FALSE
					)
				),
			),

		),
		
		'comment' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		'website' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment.website',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'ipaddress' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment.ipaddress',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'spam' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:news_comment/Resources/Private/Language/locallang_db.xlf:tx_newscomment_domain_model_comment.spam',
			'config' => array(
				'type' => 'check',
			),
		),
		'deleted' => array(
		    'exclude' => 1,
		    'label' => 'deleted',
		    'config' => array(
		        'type' => 'check',
		    ),
		),
		'pageid' => array(
			'exclude' => 1,
			'label' => 'pageid',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),

	),
);