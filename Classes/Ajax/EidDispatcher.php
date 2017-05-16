<?php
	
namespace DRCSystems\NewsComment\Ajax;

class EidDispatcher {
	/**
   * @var \array
   */
	protected $configuration;
	
	/**
   * @var \array
   */
	protected $bootstrap;
	
	/**
   * The main Method
   *
   * @return \string
   */
	public function run() {
		return $this->bootstrap->run( '', $this->configuration );
	}
	
	/**
   * Initialize Extbase
   *
   * @param \array $TYPO3_CONF_VARS
   */
	public function __construct($TYPO3_CONF_VARS) { 
		$page = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('page');

		$ajaxRequest = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_newscomment_newscomment');
		
		
		// create bootstrap
		$this->bootstrap = new \TYPO3\CMS\Extbase\Core\Bootstrap();
		
		// get User
		$feUserObj = \TYPO3\CMS\Frontend\Utility\EidUtility::initFeUser();
		
		// set PID
		$pid = (\TYPO3\CMS\Core\Utility\GeneralUtility::_GET( 'id' )) ? \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('id') : 0; 
		
		// Create and init Frontend
		$GLOBALS['TSFE'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController', $TYPO3_CONF_VARS, $pid, 0, TRUE );
		\TYPO3\CMS\Frontend\Utility\EidUtility::initLanguage();
		\TYPO3\CMS\Frontend\Utility\EidUtility::initTCA();
		$GLOBALS['TSFE']->connectToDB();
		$GLOBALS['TSFE']->fe_user = $feUserObj;
		$GLOBALS['TSFE']->id = $pid;
		$GLOBALS['TSFE']->determineId();
		//$GLOBALS['TSFE']->getCompressedTCarray();
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->getConfigArray();
		//$GLOBALS['TSFE']->includeTCA();
		$GLOBALS['TSFE']->settingLanguage();

		// Get Plugins TypoScript
		$TypoScriptService = new \TYPO3\CMS\Extbase\Service\TypoScriptService();
		$pluginConfiguration = $TypoScriptService->convertTypoScriptArrayToPlainArray($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_newscomment_newscomment.']);
		
		
		
		// Set configuration to call the plugin
		$this->configuration = array (
				'pluginName' => $ajaxRequest['pluginName'],
				'vendorName' => 'DRCSystems',
				'extensionName' => 'NewsComment',
				'controller' => $ajaxRequest['controller'],
				'action' => $ajaxRequest['action'],
				'params' => $ajaxRequest['param'],
				'mvc' => array (
						'requestHandlers' => array (
								'TYPO3\CMS\Extbase\Mvc\Web\FrontendRequestHandler' => 'TYPO3\CMS\Extbase\Mvc\Web\FrontendRequestHandler'
						)
				),
				'settings' => $pluginConfiguration['settings'],
				'persistence' => array (
						'storagePid' => $pluginConfiguration['persistence']['storagePid']
				)
		);
		
	}
}
global $TYPO3_CONF_VARS;
// make instance of bootstrap and run
$eid = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'DRCSystems\NewsComment\Ajax\EidDispatcher', $TYPO3_CONF_VARS );
echo $eid->run();

?>