<?php
namespace DRCSystems\NewsComment\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * CommentController
 */
class CommentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * commentRepository
     *
     * @var \DRCSystems\NewsComment\Domain\Repository\CommentRepository
     * @inject
     */
    protected $commentRepository = NULL;
    
    /**
     * ratingRepository
     *
     * @var \DRCSystems\NewsComment\Domain\Repository\RatingRepository
     * @inject
     */
    protected $ratingRepository = NULL;
    
    /**
     * usersRepository
     *
     * @var \DRCSystems\NewsComment\Domain\Repository\UsersRepository
     * @inject
     */
    protected $usersRepository = NULL;
    
    /**
     * @var \GeorgRinger\News\Domain\Repository\NewsRepository
     * @inject
     */
    protected $newsRepository = null;
    
    /**
     * action initialize
     *
     * @return void
     */
    public function initializeAction()
    {
        $newsArr = GeneralUtility::_GP('tx_news_pi1');
        $newsUid = $newsArr['news'];
        $this->newsUid = intval($newsUid);
        $this->pageUid = $GLOBALS['TSFE']->id;
        $this->userSessionId = $this->getUserSesssionId();
        $this->currentUser = $GLOBALS['TSFE']->fe_user->user;
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        if (empty($configuration['persistence']['storagePid'])) {
            $currentPid['persistence']['storagePid'] = GeneralUtility::_GP('id');
            $this->configurationManager->setConfiguration(array_merge($extbaseFrameworkConfiguration, $currentPid));
        }
    }
    
    /**
     * action list
     *
     * @return void
     */
    public function searchAction()
    {
        $wtFilter = array();
        $args = $this->request->getArguments();
        $wtFilter['searchterm'] = $args['searchterm'];
        $wtFilter['sort'] = $args['orderby'];
        $this->redirectToURI($this->buildUriByUid($this->pageUid, $wtFilter));
    }
    
    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        

        if ($this->settings['jQuery']['require'] == 1) {
            //Add JS files
            $jQueryVersion = trim($this->settings['jQuery']['version']);
            $jsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath('news_comment') . 'Resources/Public/jquery/' . $jQueryVersion;
            $GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile($jsFile, 'text/javascript', TRUE, FALSE, '', TRUE);
        }
        if ($this->settings['addJsValidations'] == 1) {
            //Add JS files
            $jsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath('news_comment') . 'Resources/Public/Js/validations.js';
            $GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile($jsFile, 'text/javascript', TRUE, FALSE, '', TRUE);
        }
        if ($this->newsUid) {
            $totalVotes = 0;
            $filter = GeneralUtility::_GP('filter');
            $this->view->assign('params', $filter);
            $comments = $this->commentRepository->getCommentsByNews($this->newsUid, $filter);
            if ($this->userSessionId != '') {
                $userRatings = $this->ratingRepository->getRatingBySession($this->userSessionId);
                foreach ($userRatings as $key => $value) {
                    $userCommentedUids[] = $value->getComment();
                }
            }

            foreach ($comments as $key => $value) {
                # code...
                if($this->settings['enableRatingAtLogin'] == 0){
                    if (count($userCommentedUids) > 0) {
                        if (in_array($value->getUid(), $userCommentedUids)) {
                          $value->setAllowrating(0);
                        }
                    }
                }
                else
                {
                    if($this->currentUser['uid'] != ''){
                        $ratedUser = $this->ratingRepository->getRatedUser($this->currentUser['uid'],$value->getUid());
                        if(count($ratedUser) >= 1){
                            $value->setAllowrating(0);
                        }
                    }
                }
                $totalVotes = $totalVotes + count($value->getRating());
            }
            $this->view->assign('comments', $comments);
            $this->view->assign('newsID', $this->newsUid);
            if ($this->currentUser) {
                $this->view->assign('currentuser', $this->currentUser);
            }
            for ($i = 1; $i <= 4; $i++) {
                $text = LocalizationUtility::translate('tx_newscomment_domain_model_comment.selectorderby.' . $i, 'NewsComment', array());
                if ($i >= 3 && $this->settings['enableRating'] == 0) {
                    break;
                }
                $sortOrderCombo[$i] = $text;
            }
            $this->view->assign('sortOrderCombo', $sortOrderCombo);
            $this->view->assign('totalVotes', $totalVotes);
            $this->view->assign('pageid', $this->pageUid);
        }
    }


    /**
     * action list
     *
     * @return void
     */
    public function searchcommentsAction()
    {
        
        $args = $this->request->getArguments();
        
        if(isset($args['moveto']))
        {
            if($args['commentId']!= '')
                $commentIds[] =  $args['commentId']; 

            foreach ($args as $key => $value) {
                $keyArr = explode('_', $key);
                if($keyArr[0] == 'chkmass' && $value==1)
                    $commentIds[] = $keyArr[1];
            }
            
            foreach ($commentIds as $key => $value) {
                $comment = $this->commentRepository->getByCommentid($value);

                if($args['moveto'] == 4 || $args['moveto'] == 6){
                    $comment->setSpam(false);
                    $comment->setDeleted(1);
                    $this->commentRepository->update($comment);
                }
                else
                {
                    if($args['moveto'] == 1)
                        $comment->setHidden(1);
                    if($args['moveto'] == 2)
                        $comment->setHidden(0);
                    if($args['moveto'] == 3)
                        $comment->setSpam(true);
                    if($args['moveto'] == 5)
                        $comment->setSpam(false);
                    if($args['moveto'] == 7)
                        $comment->setDeleted(0);
                    
                    $this->commentRepository->update($comment);
                }
            }
            
        }
        
        $wtFilter = array();
        $wtFilter['searchterm'] = $args['searchterm'];
        $wtFilter['newsId'] = $args['newsId'];
        $wtFilter['sort'] = $args['sort'];
        $wtFilter['order'] = $args['order'];

        
        $uriBuilder = $this->controllerContext->getUriBuilder();
        $uriBuilder->reset();
        $uriBuilder->setArguments(array(
            'tx_newscomment_web_newscommentcomments' => array(
                'action' => 'listcomments',
                'controller' => 'Comment',
                'filter' => $wtFilter
            )
        ));
        $uri = $uriBuilder->build();
        $this->redirectToUri($uri);
    }

    /**
     * action list
     *
     * @return void
     */
    public function listcommentsAction()
    {

        $args = $this->request->getArguments();

        $params = $args['filter'];
        if($args['filter']['order'] == 'asc')
            $params['order'] ='desc';
        else
            $params['order'] ='asc';
        
        $comments = $this->commentRepository->getBackendComments($args['filter']);
        foreach ($comments as $key => $value) {
            $news = $this->newsRepository->findByUid($value->getNewsuid(), false);
            
            //--- checking if news is hidden or deleted
            $newsHiddenDeleted = FALSE;
            if($news->getHidden() == TRUE || $news->getDeleted() == TRUE) {
                $newsHiddenDeleted = TRUE;
            }
            $value->setIsNewsHiddenOrDeleted($newsHiddenDeleted);
            //---
            
            $newstitle = $news->getTitle();
            $value->setNewstitle($newstitle);

            $newsComments = $this->commentRepository->getByNews($value->getNewsuid());
           
            $pageUid = $value->getPageid();
            if($pageUid != ''){
              $value->setPosturl($this->buildPostUrl($pageUid,$value->getNewsuid()));
            }
            else
              $value->setPosturl(''); 
            $value->setTotalcomments(count($newsComments));
        } 
        $this->view->assign('comments', $comments);
        $this->view->assign('params', $params);

        //get counts
        $array['type'] = 1;
        $commentsCounts['total'] = $this->commentRepository->getBackendComments($array,$isCount = 1);
        $array['type'] = 2;
        $commentsCounts['pending'] = $this->commentRepository->getBackendComments($array,$isCount = 1);
        $array['type'] = 3;
        $commentsCounts['approved'] = $this->commentRepository->getBackendComments($array,$isCount = 1);
        $array['type'] = 4;
        $commentsCounts['spam'] = $this->commentRepository->getBackendComments($array,$isCount = 1);
        $array['type'] = 5;
        $commentsCounts['trash'] = $this->commentRepository->getBackendComments($array,$isCount = 1);
        $this->view->assign('commentsCounts', $commentsCounts);

        //get action combo list

        $allOptions = array(1,2,3,4);
        $pendingOptions = array(2,3,4);
        $approveOptions = array(1,3,4);
        $spamOptions = array(5,4);
        $trashOptions = array(7,3);
        $actionlistCombo = array();


        $optionsArray = array('approveTypes'=>array(1,2),
                              'unapproveTypes'=>array(1,3),
                              'spamTypes'=>array(1,2,3,5),
                              'trashTypes'=>array(1,2,3,4),
                              'restoreTypes'=>array(5),
                              'replyTypes'=>array(1,2,3),
                              'quickeditTypes'=>array(1,2,3),
                              'notspamTypes'=>array(4),
                              );

        for ($i = 1; $i <= 7; $i++) {
                
                $text = LocalizationUtility::translate('tx_newscomment_domain_model_comment.selectactions.' . $i, 'NewsComment', array());
                if($params['type'] == 2 && in_array($i, $pendingOptions))
                    $actionlistCombo[$i] = $text;
                else if($params['type'] == 3 && in_array($i, $approveOptions))
                    $actionlistCombo[$i] = $text;
                else if($params['type'] == 4 && in_array($i, $spamOptions))
                    $actionlistCombo[$i] = $text;
                else if($params['type'] == 5 && in_array($i, $trashOptions))
                    $actionlistCombo[$i] = $text;
                else if(($params['type'] == 1) && in_array($i, $allOptions))
                    $actionlistCombo[$i] = $text;
                else
                {
                    if(!isset($params['type']) && in_array($i, $allOptions))
                        $actionlistCombo[$i] = $text;
                }
              
        }
        $this->view->assign('actionlistCombo', $actionlistCombo);
        $this->view->assign('optionsArray', $optionsArray);
    }

    /**
     * action reply
     *
     * @return void
     */
    public function replyAction(\DRCSystems\NewsComment\Domain\Model\Comment $replyComment)
    {

        $replyComment->setRate(0);
        $replyComment->setCrdate(time());
        $sessionValue = $this->getUserSesssionId();
        $replyComment->setUsersession($sessionValue);
        if($this->settings['notification']['siteadmin']['adminEmail'])
            $replyComment->setUsermail($this->settings['notification']['siteadmin']['adminEmail']);
        $replyComment->setUsername('admin');

        $clientIpAddress = $this->getClientIP();
        if ($clientIpAddress != '') {
            $replyComment->setIpaddress($clientIpAddress);
        }
        $this->commentRepository->add($replyComment);
        $this->getPersistenceManager()->persistAll();
        //inserting comment reaply
        $args = $this->request->getArguments();
        $parentId = $args['parentId'];
        if ($parentId) {
            $parentComment = $this->commentRepository->findByUid($parentId);
            $parentComment->addParentcomment($replyComment);
            $this->commentRepository->update($parentComment);
            $this->getPersistenceManager()->persistAll();
        }
        $this->redirect('listcomments');
    }
    
    /**
     * action show
     *
     * @param \DRCSystems\NewsComment\Domain\Model\Comment $comment
     * @return void
     */
    public function showAction(\DRCSystems\NewsComment\Domain\Model\Comment $comment)
    {
        $this->view->assign('comment', $comment);
    }
    
    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {
        
    }
    
    /**
     * A template method for displaying custom error flash messages, or to
     * display no flash message at all on errors. Override this to customize
     * the flash message in your action controller.
     *
     * @api
     * @return string|boolean The flash message or FALSE if no flash message should be set
     */
    protected function getErrorFlashMessage()
    {
        return FALSE;
    }
    
   
    /**
     * action create
     *
     * @param \DRCSystems\NewsComment\Domain\Model\Comment $newComment
     * @validate $newComment \DRCSystems\NewsComment\Utility\Validator\CustomValidator
     * @return void
     */
    public function createAction(\DRCSystems\NewsComment\Domain\Model\Comment $newComment)
    {

        $translateArguments = array(
            'username' => $newComment->getUsername(),
            'usermail' => $newComment->getUsermail(),
            'description' => $newComment->getDescription()
        );
        $username = $newComment->getUsername();
        $useremail = $newComment->getUsermail();
        if ($this->currentUser['uid']) {
            $userObj = $this->usersRepository->findByUid($this->currentUser['uid']);
            $newComment->setUser($userObj);
            $username = $this->currentUser['username'];
            $useremail = $this->currentUser['email'];
        }
        $newComment->setRate(0);
        $newComment->setCrdate(time());
        $sessionValue = $this->getUserSesssionId();
        $newComment->setUsersession($sessionValue);
        $clientIpAddress = $this->getClientIP();
        if ($clientIpAddress != '') {
            $newComment->setIpaddress($clientIpAddress);
        }
        if ($this->settings['requireCommentApproval'] == 1) {
            $newComment->setHidden(1);
            $this->addFlashMessage(LocalizationUtility::translate('tx_newscomment_domain_model_comment.comment_approval_msg', 'NewsComment', $translateArguments));
        } else {
            $this->addFlashMessage(LocalizationUtility::translate('tx_newscomment_domain_model_comment.comment_succ_msg', 'NewsComment', $translateArguments));
        }
        $args = $this->request->getArguments();
        $parentId = $args['parentId'];
        $this->commentRepository->add($newComment);
        $this->getPersistenceManager()->persistAll();
        //inserting comment reaply
        if ($parentId) {
            $parentComment = $this->commentRepository->findByUid($parentId);
            $parentComment->addParentcomment($newComment);
            $this->commentRepository->update($parentComment);
            $this->getPersistenceManager()->persistAll();
        }
        $news = $this->newsRepository->findByUid($this->newsUid);
        $authorMailAddress = $news->getAuthorEmail();
        if ($authorMailAddress != '' && $this->settings['notification']['newsauthor']['sendMailToNewsAuthor'] == 1) {
            $variables['mail'] = array(
                'description' => $newComment->getDescription(),
                'username' => $username,
                'usermail' => $useremail,
                'tomail' => $authorMailAddress
            );
            $this->sendNewCommentMailToAuthor($variables);
        }
        if ($this->settings['notification']['siteadmin']['sendMailToAdmin'] == 1) {
            $variables['mail'] = array(
                'description' => $newComment->getDescription(),
                'username' => $username,
                'usermail' => $useremail
            );
            $this->sendNewCommentMailToAdmin($variables);
        }
        $this->redirectToURI($this->buildUriByUid($this->pageUid, $arguments = array()));
        return FALSE;
    }
    
    /**
     * @param $variables
     */
    public function sendNewCommentMailToAdmin($variables)
    {
        $template = 'Email/mailNewCommentToAdmin.html';
        $senderMail = $this->settings['notification']['senderEmail'];
        $senderFrom = $this->settings['notification']['senderName'];
        $sender = array($senderMail => $senderFrom);
        $recipient = $this->settings['notification']['siteadmin']['adminEmail'];
        $subject = $this->settings['notification']['siteadmin']['adminMailSubject'];
        $this->sendTemplateEmail($recipient, $sender, $subject, $template, $variables);
    }
    
    /**
     * @param $variables
     */
    public function sendNewCommentMailToAuthor($variables)
    {
        $template = 'Email/mailNewCommentToAuthor.html';
        $senderMail = $this->settings['notification']['senderEmail'];
        $senderFrom = $this->settings['notification']['senderName'];
        $sender = array($senderMail => $senderFrom);
        $recipient = $variables['mail']['tomail'];
        $subject = $this->settings['notification']['newsauthor']['authorMailSubject'];
        $this->sendTemplateEmail($recipient, $sender, $subject, $template, $variables);
    }
    
    /**
     * @param $recipient
     * @param $sender
     * @param $subject
     * @param $template
     * @param $variables
     */
    public function sendTemplateEmail($recipient, $sender, $subject, $template, $variables = array())
    {
        $emailView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $templateRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templateRootPaths'][0]);
        $templatePathAndFilename = $templateRootPath . $template;
        $emailView->setTemplatePathAndFilename($templatePathAndFilename);
        $emailView->assignMultiple($variables);
        $emailBody = html_entity_decode($emailView->render());
        $message = $this->objectManager->get('TYPO3\\CMS\\Core\\Mail\\MailMessage');
        $message->setTo($recipient)->setFrom($sender)->setSubject($subject);
        // HTML Email
        $message->setBody($emailBody, 'text/html');
        $message->send();
        return $message->isSent();
    }
    
    /**
     * Returns a built URI by pageUid
     *
     * @param int $uid The uid to use for building link
     * @param bool $arguments
     * @return string The link
     */
    private function buildUriByUid($uid, $arguments = array())
    {
        $excludeFromQueryString = array('tx_newscomment_newscomment[action]', 'tx_newscomment_newscomment[controller]');
        $argumentsToBeIncluded = array('filter' => $arguments);
        $uri = $this->uriBuilder->setTargetPageUid($uid)->setAddQueryString(TRUE)->setArgumentsToBeExcludedFromQueryString($excludeFromQueryString)->setArguments($argumentsToBeIncluded)->build();
        $uri = $this->addBaseUriIfNecessary($uri);
        return $uri;
    }
    
    /**
     * Returns a built URI by pageUid
     *
     * @param int $uid The uid to use for building link
     * @param bool $arguments
     * @return string The link
     */
    private function buildPostUrl($uid, $newsUid)
    {
        $newsQueryString = '&tx_news_pi1[news]='.$newsUid.'&tx_news_pi1[controller]=News';
        $uri = $this->settings['siteURL'].'index.php?id='.$uid.$newsQueryString;
        return $uri;
    }

    /**
     * action edit
     *
     * @param \DRCSystems\NewsComment\Domain\Model\Comment $comment
     * @ignorevalidation $comment
     * @return void
     */
    public function editAction(\DRCSystems\NewsComment\Domain\Model\Comment $comment)
    {
        $this->view->assign('comment', $comment);
    }
    
    /**
     * action update
     *
     * @return void
     */
    public function updateAction()
    {
        $args = $this->request->getArguments();
        $username = $args['comment']['username'];
        $usermail = $args['comment']['usermail'];
        $description = $args['comment']['description'];
        $website = $args['comment']['website'];
        $commentId = $args['commentId'];
        $comment = $this->commentRepository->getByCommentid($commentId);
        $comment->setUsername($username);
        $comment->setUsermail($usermail);
        $comment->setDescription($description);
        $comment->setWebsite($website);
        $this->commentRepository->update($comment);
        $this->getPersistenceManager()->persistAll();
        $this->redirect('listcomments');
    }
    
    /**
     * action delete
     *
     * @param \DRCSystems\NewsComment\Domain\Model\Comment $comment
     * @return void
     */
    public function deleteAction(\DRCSystems\NewsComment\Domain\Model\Comment $comment)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->commentRepository->remove($comment);
        $this->redirect('list');
    }
    
    /**
     * action list
     *
     * @return void
     */
    public function addratingAction()
    {
        $paramArr = GeneralUtility::_GET('param');
        $rate = $paramArr['rate'];
        $commentId = $paramArr['commentid'];
        if (isset($rate) && isset($commentId)) {
            //add rating
            $newRating = new \DRCSystems\NewsComment\Domain\Model\Rating();
            $newRating->setRate($rate);
            $this->createUserSessinoId(1);
            $newRating->setUsersession($this->userSessionId);
            if (isset($this->currentUser['uid'])) {
                $userObj = $this->usersRepository->findByUid($this->currentUser['uid']);
                $newRating->setUser($userObj);
            }
            $this->ratingRepository->add($newRating);
            $this->getPersistenceManager()->persistAll();
            //update commment with newrating
            $commentObj = $this->commentRepository->findByUid($commentId);
            $commentObj->addRating($newRating);
            $this->commentRepository->update($commentObj);
            $this->getPersistenceManager()->persistAll();
            //calculate rating value and update
            $ratingArr = $this->ratingRepository->countRatingComment($commentId);
            $ratingVal = $ratingArr['rating'];
            $commentObj->setRate($ratingVal);
            $this->commentRepository->update($commentObj);
            $this->getPersistenceManager()->persistAll();
            $aJson['succ'] = 1;
            $json = json_encode($aJson);
            print $json;
            die;
        }
    }
    
    /**
     * Creates a unique string for author identification
     *
     * @param $rating
     * @return void
     */
    protected function createUserSessinoId($rating = 0)
    {
        if ($this->userSessionId === NULL) {
            if ($rating == 1) {
                $number_of_days = 30;
                $date_of_expiry = time() + 60 * 60 * 24 * $number_of_days;
            } else {
                $date_of_expiry = time() + 60 * 20;
            }
            $this->userSessionId = uniqid() . uniqid();
            setcookie('userlogin', $this->userSessionId, $date_of_expiry, '/');
            return $this->userSessionId;
        }
    }
    
    /**
     * Creates a unique string for author identification
     *
     * @return void
     */
    protected function getUserSesssionId()
    {
        return $_COOKIE['userlogin'];
    }
    
    /**
     * Get PersistenceManager
     *
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected function getPersistenceManager()
    {
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        return $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    }
    
    /**
     * Get client IP Address
     *
     * @return void
     */
    public function getClientIP()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                return $_SERVER['HTTP_CLIENT_IP'];
            }
            return $_SERVER['REMOTE_ADDR'];
        }
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            return getenv('HTTP_X_FORWARDED_FOR');
        }
        if (getenv('HTTP_CLIENT_IP')) {
            return getenv('HTTP_CLIENT_IP');
        }
        return getenv('REMOTE_ADDR');
    }

}