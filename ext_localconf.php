<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'DRCSystems.' . $_EXTKEY,
	'Newscomment',
	array(
		'Comment' => 'list, new, show, create, edit, update, delete, addrating, search, searchcomments, updatecomments, reply',
		'Rating' => 'list, show, new, create, edit, update, delete',
		
	),
	// non-cacheable actions
	array(
		'Comment' => 'create, update, delete, search',
		'Rating' => 'create, update, delete',
		
	)
);


$TYPO3_CONF_VARS['FE']['eID_include']['Newscommentajax'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('news_comment').'Classes/Ajax/EidDispatcher.php';