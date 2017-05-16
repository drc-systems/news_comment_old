<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'DRCSystems.' . $_EXTKEY,
	'Newscomment',
	'News Comment'
);

if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'DRCSystems.' . $_EXTKEY,
		'web',	 // Make module a submodule of 'web'
		'comments',	// Submodule key
		'',						// Position
		array(
			'Comment' => 'listcomments, updatecomments, searchcomments, reply, list, show, new, create, edit, update, delete','Rating' => 'list, show, new, create, edit, update, delete',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_comments.xlf',
		)
	);

}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'News Comment');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_newscomment_domain_model_comment', 'EXT:news_comment/Resources/Private/Language/locallang_csh_tx_newscomment_domain_model_comment.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_newscomment_domain_model_comment');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_newscomment_domain_model_rating', 'EXT:news_comment/Resources/Private/Language/locallang_csh_tx_newscomment_domain_model_rating.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_newscomment_domain_model_rating');
