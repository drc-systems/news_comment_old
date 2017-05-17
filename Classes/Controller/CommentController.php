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
    const COMMENT_FILTER_TYPE_ALL = 1;
    const COMMENT_FILTER_TYPE_PENDING = 2;
    const COMMENT_FILTER_TYPE_APPROVE = 3;
    const COMMENT_FILTER_TYPE_SPAM = 4;
    const COMMENT_FILTER_TYPE_TRASH = 5;
    const COMMENT_FILTER_TYPE_RESTORE = 7;
    const SORT_ORDER_ASC = 'asc';
    const SORT_ORDER_DESC = 'desc';

    /**
     * commentRepository
     *
     * @var \DRCSystems\NewsComment\Domain\Repository\CommentRepository
     * @inject
     */
    protected $commentRepository = null;
    
    /**
     * ratingRepository
     *
     * @var \DRCSystems\NewsComment\Domain\Repository\RatingRepository
     * @inject
     */
    protected $ratingRepository = null;
    
    /**
     * usersRepository
     *
     * @var \DRCSystems\NewsComment\Domain\Repository\UsersRepository
     * @inject
     */
    protected $usersRepository = null;
    
    /**
     * @var \GeorgRinger\News\Domain\Repository\NewsRepository
     * @inject
     */
    protected $newsRepository = null;

    /**
     * @var array $currentUser currentUser
     */
    protected $currentUser = null;

    /**
     * @var string $userSessionId userSessionId
     */
    protected $userSessionId = null;

    /**
     * @var int $newsUid newsUid
     */
    protected $newsUid;

    /**
     * @var int $pageUid pageUid
     */
    protected $pageUid;

    /**
     * @param \TYPO3\CMS\Core\Cache\CacheManager $cacheManager
     */
    public function injectCacheManager(\TYPO3\CMS\Core\Cache\CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }
    
    /**
     * Action initialize
     *
     * @return void
     */
    public function initializeAction()
    {
        $newsArr = GeneralUtility::_GP('tx_news_pi1');
        $this->newsUid = intval($newsArr['news']);
        $this->pageUid = $GLOBALS['TSFE']->id;
        $this->userSessionId = $this->getUserSesssionId();
        $this->currentUser = $GLOBALS['TSFE']->fe_user->user;
        $xBaseConfiguration = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );
        if (empty($xBaseConfiguration['persistence']['storagePid'])) {
            $currentPid['persistence']['storagePid'] = $this->pageUid;
            $this->configurationManager->setConfiguration(array_merge($xBaseConfiguration, $currentPid));
        }
    }
    
    /**
     * Action search
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
     * Action search comments
     *
     * @return void
     */
    public function searchCommentsAction()
    {
        $args = $this->request->getArguments();
        if (isset($args['moveto'])) {
            $commentIds = array();
            if ($args['commentId'] != '') {
                $commentIds[] =  $args['commentId'];
            }
               
            foreach ($args as $key => $value) {
                $keyArr = explode('_', $key);
                if ($keyArr[0] == 'chkmass' && $value == 1) {
                    $commentIds[] = $keyArr[1];
                }
            }
        
            foreach ($commentIds as $value) {
                $comment = $this->commentRepository->getCommentById($value);
                if ($args['moveto'] == self::COMMENT_FILTER_TYPE_SPAM) {
                    $comment->setSpam(false);
                    $comment->setDeleted(1);
                } else {
                    if ($args['moveto'] == self::COMMENT_FILTER_TYPE_ALL) {
                        $comment->setHidden(1);
                    }
                    if ($args['moveto'] == self::COMMENT_FILTER_TYPE_PENDING) {
                        $comment->setHidden(0);
                    }
                    if ($args['moveto'] == self::COMMENT_FILTER_TYPE_APPROVE) {
                        $comment->setSpam(true);
                    }
                    if ($args['moveto'] == self::COMMENT_FILTER_TYPE_TRASH) {
                        $comment->setSpam(false);
                    }
                    if ($args['moveto'] == self::COMMENT_FILTER_TYPE_RESTORE) {
                        $comment->setDeleted(0);
                    }
                }
                $this->commentRepository->update($comment);
            }
        }
        $wtFilter = array();
        $wtFilter['searchterm'] = $args['searchterm'];
        $wtFilter['newsId'] = $args['newsId'];
        $wtFilter['sort'] = $args['sort'];
        $wtFilter['order'] = $args['order'];
        
        $uri = $this->controllerContext->getUriBuilder()
            ->reset()
            ->setArguments(
                array(
                    'tx_newscomment_web_newscommentcomments' => array(
                        'action' => 'listComments',
                        'controller' => 'Comment',
                        'filter' => $wtFilter
                    )
                )
            )->build();
        $this->redirectToUri($uri);
    }

    /**
     * Action list comments
     *
     * @return void
     */
    public function listCommentsAction()
    {
        $args = $this->request->getArguments();
        $params = $args['filter'];
        if ($args['filter']['order'] == self::SORT_ORDER_ASC) {
            $params['order'] = self::SORT_ORDER_DESC;
        } else {
            $params['order'] = self::SORT_ORDER_ASC;
        }

        $comments = $this->commentRepository->getBackendComments($args['filter']);
        foreach ($comments as $value) {
            $news = $this->newsRepository->findByUid($value->getNewsuid(), false);
            
            //--- checking if news is hidden or deleted
            $newsHiddenDeleted = false;
            if ($news->getHidden() == true || $news->getDeleted() == true) {
                $newsHiddenDeleted = true;
            }
            $value->setIsNewsHiddenOrDeleted($newsHiddenDeleted);
            //---
                
            $newstitle = $news->getTitle();
            $value->setNewstitle($newstitle);

            $newsComments = $this->commentRepository->getCommentByNews($value->getNewsuid());
         
            $pageUid = $value->getPageid();
            if ($pageUid != '') {
                $value->setPosturl(
                    $this->buildPostUrl($pageUid, $value->getNewsuid())
                );
            } else {
                $value->setPosturl('');
            }
            $value->setTotalcomments(count($newsComments));
        }
        $this->view->assign('comments', $comments);
        $this->view->assign('params', $params);

        //get counts
        $isCount = true;
        $array['type'] = self::COMMENT_FILTER_TYPE_ALL;
        $commentsCounts['total'] = $this->commentRepository->getBackendComments($array, $isCount);
        $array['type'] = self::COMMENT_FILTER_TYPE_PENDING;
        $commentsCounts['pending'] = $this->commentRepository->getBackendComments($array, $isCount);
        $array['type'] = self::COMMENT_FILTER_TYPE_APPROVE;
        $commentsCounts['approved'] = $this->commentRepository->getBackendComments($array, $isCount);
        $array['type'] = self::COMMENT_FILTER_TYPE_SPAM;
        $commentsCounts['spam'] = $this->commentRepository->getBackendComments($array, $isCount);
        $array['type'] = self::COMMENT_FILTER_TYPE_TRASH;
        $commentsCounts['trash'] = $this->commentRepository->getBackendComments($array, $isCount);
        $this->view->assign('commentsCounts', $commentsCounts);

        //get action combo list
        $allOptions = array(
            self::COMMENT_FILTER_TYPE_ALL,
            self::COMMENT_FILTER_TYPE_PENDING,
            self::COMMENT_FILTER_TYPE_APPROVE,
            self::COMMENT_FILTER_TYPE_SPAM
        );
        $pendingOptions = array(
            self::COMMENT_FILTER_TYPE_PENDING,
            self::COMMENT_FILTER_TYPE_APPROVE,
            self::COMMENT_FILTER_TYPE_SPAM
        );
        $approveOptions = array(
            self::COMMENT_FILTER_TYPE_ALL,
            self::COMMENT_FILTER_TYPE_APPROVE,
            self::COMMENT_FILTER_TYPE_SPAM
        );
        $spamOptions = array(
            self::COMMENT_FILTER_TYPE_TRASH,
            self::COMMENT_FILTER_TYPE_SPAM
        );
        $trashOptions = array(
            self::COMMENT_FILTER_TYPE_RESTORE,
            self::COMMENT_FILTER_TYPE_APPROVE
        );
        
        $optionsArray = $this->getDDOptions();
        $actionlistCombo = array();
        for ($i = 1; $i <= 7; $i++) {
            $text = LocalizationUtility::translate(
                'tx_newscomment_domain_model_comment.selectactions.' . $i,
                'NewsComment',
                array()
            );
            if ($params['type'] == self::COMMENT_FILTER_TYPE_PENDING && in_array($i, $pendingOptions)) {
                $actionlistCombo[$i] = $text;
            } elseif ($params['type'] == self::COMMENT_FILTER_TYPE_APPROVE && in_array($i, $approveOptions)) {
                $actionlistCombo[$i] = $text;
            } elseif ($params['type'] == self::COMMENT_FILTER_TYPE_SPAM && in_array($i, $spamOptions)) {
                $actionlistCombo[$i] = $text;
            } elseif ($params['type'] == self::COMMENT_FILTER_TYPE_TRASH && in_array($i, $trashOptions)) {
                $actionlistCombo[$i] = $text;
            } elseif (($params['type'] == self::COMMENT_FILTER_TYPE_ALL) && in_array($i, $allOptions)) {
                $actionlistCombo[$i] = $text;
            } else {
                if (!isset($params['type']) && in_array($i, $allOptions)) {
                    $actionlistCombo[$i] = $text;
                }
            }
        }
        $this->view->assign('actionlistCombo', $actionlistCombo);
        $this->view->assign('optionsArray', $optionsArray);
    }

    /**
     * Function get dropdown option in backend
     *
     * @return array
     */
    private function getDDOptions()
    {
        return array(
            'approveTypes' => array(1,2),
            'unapproveTypes' => array(1,3),
            'spamTypes' => array(1,2,3,5),
            'trashTypes' => array(1,2,3,4),
            'restoreTypes' => array(5),
            'replyTypes' => array(1,2,3),
            'quickeditTypes' => array(1,2,3),
            'notspamTypes' => array(4),
        );
    }

    /**
     * Action reply
     *
     * @param \DRCSystems\NewsComment\Domain\Model\Comment $replyComment replyComment
     *
     * @return void
     */
    public function replyAction(\DRCSystems\NewsComment\Domain\Model\Comment $replyComment)
    {
        $replyComment->setRate(0);
        $replyComment->setCrdate(time());
        $sessionValue = $this->getUserSesssionId();
        $replyComment->setUsersession($sessionValue);
        if ($this->settings['notification']['siteadmin']['adminEmail']) {
            $replyComment->setUsermail(
                $this->settings['notification']['siteadmin']['adminEmail']
            );
        }
        $replyComment->setUsername('admin');
        $clientIpAddress = $this->getClientIP();
        if ($clientIpAddress != '') {
            $replyComment->setIpaddress($clientIpAddress);
        }
        $this->commentRepository->add($replyComment);
        $this->getPersistenceManager()->persistAll();
        //inserting comment reaply
        $args = $this->request->getArguments();
        $parentId = intval($args['parentId']);
        if ($parentId) {
            $parentComment = $this->commentRepository->findByUid($parentId);
            $parentComment->addParentcomment($replyComment);
            $this->commentRepository->update($parentComment);
            $this->getPersistenceManager()->persistAll();
        }
        $this->redirect('listComments');
    }

    /**
     * Action new
     *
     * @return void
     */
    public function newAction()
    {
        $this->view->assign('settings', $this->settings);
        if ($this->settings['jQuery']['require'] == 1) {
            //Add JS files
            $jQueryVersion = trim($this->settings['jQuery']['version']);
            $jsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath(
                'news_comment'
            ) . 'Resources/Public/jquery/' . $jQueryVersion;
            $GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile($jsFile, 'text/javascript', true, false, '', true);
        }
        if ($this->settings['addJsValidations'] == 1) {
            //Add JS files
            $jsFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath(
                'news_comment'
            ) . 'Resources/Public/Js/validations.js';
            $GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile($jsFile, 'text/javascript', true, false, '', true);
        }
        if ($this->newsUid) {
            $totalVotes = 0;
            $filter = GeneralUtility::_GP('filter');
            $this->view->assign('params', $filter);
            $comments = $this->commentRepository->getCommentsByNews($this->newsUid, $filter);
            if ($this->userSessionId != '') {
                $userRatings = $this->ratingRepository->getRatingBySession($this->userSessionId);
                foreach ($userRatings as $value) {
                    $userCommentedUids[] = $value->getComment();
                }
            }
            foreach ($comments as $value) {
                # code...
                if ($this->settings['enableRatingAtLogin'] == 0) {
                    if (count($userCommentedUids) > 0) {
                        if (in_array($value->getUid(), $userCommentedUids)) {
                            $value->setAllowrating(0);
                        }
                    }
                } else {
                    if ($this->currentUser['uid'] != '') {
                        $ratedUser = $this->ratingRepository->getRatedUser($this->currentUser['uid'], $value->getUid());
                        if (count($ratedUser) >= 1) {
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
                $text = LocalizationUtility::translate(
                    'tx_newscomment_domain_model_comment.selectorderby.' . $i,
                    'NewsComment',
                    array()
                );
                if ($i >= 3 && $this->settings['enableRating'] == 0) {
                    break;
                }
                $sortOrderCombo[$i] = $text;
            }
            $this->view->assign('sortOrderCombo', $sortOrderCombo);
            $this->view->assign('totalVotes', $totalVotes);
            $this->view->assign('pageid', $this->pageUid);
        }
        $this->view->assign('newComment', $newComment);
    }
    
    /**
     * A template method for displaying custom error flash messages, or to
     * display no flash message at all on errors. Override this to customize
     * the flash message in your action controller.
     *
     * @api
     * @return string|boolean The flash message or false if no flash message should be set
     */
    protected function getErrorFlashMessage()
    {
        return '';
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
        // DebuggerUtility::var_dump($newComment); exit;

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
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'tx_newscomment_domain_model_comment.comment_approval_msg',
                    'NewsComment',
                    $translateArguments
                )
            );
        } else {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'tx_newscomment_domain_model_comment.comment_succ_msg',
                    'NewsComment',
                    $translateArguments
                )
            );
        }
        $args = $this->request->getArguments();
        $parentId = intval($args['parentId']);
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
        $this->cacheManager->flushCachesInGroupByTag('pages', 'tx_news_uid_' . $newComment->getNewsUid());
        $this->redirectToURI($this->buildUriByUid($this->pageUid, array()));
    }
    
    /**
     * Send comment to admin 
     *
     * @param array $variables variables
     *
     * @return void
     */
    public function sendNewCommentMailToAdmin(array $variables = array())
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
     * Send comment to author 
     *
     * @param array $variables variables
     *
     * @return void
     */
    public function sendNewCommentMailToAuthor(array $variables = array())
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
     * Send email
     *    
     * @param string $recipient recipient
     * @param string $sender sender
     * @param string $subject subject
     * @param string $template template
     * @param array $variables variables
     *
     * @return void
     */
    public function sendTemplateEmail($recipient, $sender, $subject, $template, array $variables = array())
    {
        $emailView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
        $xBaseConfiguration = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );
        $templateRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(
            $xBaseConfiguration['view']['templateRootPaths'][0]
        );
        $templateFile = $templateRootPath . $template;
        $emailView->setTemplatePathAndFilename($templateFile);
        $emailView->assignMultiple($variables);
        $emailBody = html_entity_decode($emailView->render());
        $message = $this->objectManager->get('TYPO3\\CMS\\Core\\Mail\\MailMessage');
        $message->setTo($recipient)->setFrom($sender)->setSubject($subject);
        // HTML Email
        $message->setBody($emailBody, 'text/html');
        $message->send();
        $message->isSent();
    }
    
    /**
     * Returns a built URI by pageUid
     *
     * @param int $uid The uid to use for building link
     * @param array $arguments
     * @return string The link
     */
    private function buildUriByUid($uid, array $arguments = array())
    {
        $excludeVars = array(
            'tx_newscomment_newscomment[action]',
            'tx_newscomment_newscomment[controller]'
        );
        $arguments = array('filter' => $arguments);
        $uri = $this->uriBuilder
                ->setTargetPageUid($uid)
                ->setAddQueryString(true)
                ->setArgumentsToBeExcludedFromQueryString($excludeVars)
                ->setArguments($arguments)->build();
        $uri = $this->addBaseUriIfNecessary($uri);
        return $uri;
    }
    
    /**
     * Returns a built URI by pageUid
     *
     * @param int $uid The uid to use for building link
     * @param int $newsUid
     * @return string
     */
    private function buildPostUrl($uid, $newsUid)
    {
        $newsQueryString = '&tx_news_pi1[news]='.$newsUid.'&tx_news_pi1[controller]=News';
        $uri = $this->settings['siteURL'].'index.php?id='.$uid.$newsQueryString;
        return $uri;
    }
    
    /**
     * Action update
     *
     * @return void
     */
    public function updateAction()
    {
        $args = $this->request->getArguments();
        if (intval($args['commentId'])) {
            $username = $args['comment']['username'];
            $usermail = $args['comment']['usermail'];
            $description = $args['comment']['description'];
            $website = $args['comment']['website'];
            $commentId = intval($args['commentId']);
            $comment = $this->commentRepository->getCommentById($commentId);
            $comment->setUsername($username);
            $comment->setUsermail($usermail);
            $comment->setDescription($description);
            $comment->setWebsite($website);
            $this->commentRepository->update($comment);
            $this->getPersistenceManager()->persistAll();
            $this->cacheManager->flushCachesInGroupByTag('pages', 'tx_news_uid_' . $comment->getNewsUid());
            $this->redirect('listComments');
        }
    }
    
    /**
     * Action add rating
     *
     * @return void
     */
    public function addRatingAction()
    {
        if ($this->currentUser) {
            $paramArr = GeneralUtility::_GET('param');
            $rate = $paramArr['rate'];
            $commentId = intval($paramArr['commentid']);

            // $aJson['succ'] = 1;
            // $json = json_encode($aJson);
            // print $json;
            // die;

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
        } else {
            die('Action not allowed');
        }
    }
    
    /**
     * Creates a unique string for author identification
     *
     * @param int $rating rating
     * @return string
     */
    protected function createUserSessinoId($rating = 0)
    {
        if ($this->userSessionId === null) {
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
     * @return string
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
     * @return string
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
