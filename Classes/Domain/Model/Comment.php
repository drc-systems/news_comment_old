<?php
namespace DRCSystems\NewsComment\Domain\Model;

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
 * Comment
 */
class Comment extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * crdate as unix timestamp
     *
     * @var int
     */
    protected $crdate = 0;
    
    /**
     * votes
     *
     * @var int
     */
    protected $votes = 0;
    
    /**
     * hidden
     *
     * @var bool
     */
    protected $hidden = 0;

    /**
     * deleted
     *
     * @var bool
     */
    protected $deleted = 0;
    
    /**
     * allowrating
     *
     * @var int
     */
    protected $allowrating = 1;
    
    /**
     * newsuid
     *
     * @var int
     */
    protected $newsuid = 0;
    
    /**
     * username
     *
     * @var string
     */
    protected $username = '';
    
    /**
     * usermail
     *
     * @var string
     */
    protected $usermail = '';
    
    /**
     * usersession
     *
     * @var string
     */
    protected $usersession = '';
    
    /**
     * description
     *
     * @var string
     */
    protected $description = '';
    
    /**
     * rate
     *
     * @var float
     */
    protected $rate = '';
    
    /**
     * ipaddress
     *
     * @var string
     */
    protected $ipaddress = '';
    
    /**
     * spam
     *
     * @var bool
     */
    protected $spam = false;
    
    /**
     * website
     *
     * @var string
     */
    protected $website = '';

    /**
     * uid
     *
     * @var int
     */
    protected $uid;

    /**
     * pageid
     *
     * @var int
     */
    protected $pageid;

    /**
     * totalcomments
     *
     * @var int
     */
    protected $totalcomments;
    
    /**
     * user
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    protected $user = null;
    
    /**
     * rating
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DRCSystems\NewsComment\Domain\Model\Rating>
     * @cascade remove
     */
    protected $rating = null;
    
    /**
     * parentcomment
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DRCSystems\NewsComment\Domain\Model\Comment>
     * @cascade remove
     */
    protected $parentcomment = null;

    /**
     * posturl
     *
     * @var string
     */
    protected $posturl = '';

    /**
     * newstitle
     *
     * @var string
     */
    protected $newstitle = '';

    /**
     * check if news is hidden or deleted
     *
     * @var boolean
     * @transient
     */
    protected $isNewsHiddenOrDeleted = false;
    
    /**
     * Returns the newsuid
     *
     * @return int $newsuid
     */
    public function getNewsuid()
    {
        return $this->newsuid;
    }
    
    /**
     * Sets the newsuid
     *
     * @param int $newsuid
     * @return void
     */
    public function setNewsuid($newsuid)
    {
        $this->newsuid = $newsuid;
    }
    
    /**
     * Returns the username
     *
     * @return string $username
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * Sets the username
     *
     * @param string $username
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    
    /**
     * Returns the usermail
     *
     * @return string $usermail
     */
    public function getUsermail()
    {
        return $this->usermail;
    }
    
    /**
     * Sets the usermail
     *
     * @param string $usermail
     * @return void
     */
    public function setUsermail($usermail)
    {
        $this->usermail = $usermail;
    }
    
    /**
     * Returns the usersession
     *
     * @return string $usersession
     */
    public function getUsersession()
    {
        return $this->usersession;
    }
    
    /**
     * Sets the usersession
     *
     * @param string $usersession
     * @return void
     */
    public function setUsersession($usersession)
    {
        $this->usersession = $usersession;
    }
    
    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * Returns the user
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $user
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Sets the user
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $user
     * @return void
     */
    public function setUser(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $user)
    {
        $this->user = $user;
    }
    
    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }
    
    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->rating = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->parentcomment = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }
    
    /**
     * Adds a Rating
     *
     * @param \DRCSystems\NewsComment\Domain\Model\Rating $rating
     * @return void
     */
    public function addRating(\DRCSystems\NewsComment\Domain\Model\Rating $rating)
    {
        $this->rating->attach($rating);
    }
    
    /**
     * Removes a Rating
     *
     * @param \DRCSystems\NewsComment\Domain\Model\Rating $ratingToRemove The Rating to be removed
     * @return void
     */
    public function removeRating(\DRCSystems\NewsComment\Domain\Model\Rating $ratingToRemove)
    {
        $this->rating->detach($ratingToRemove);
    }
    
    /**
     * Returns the rating
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DRCSystems\NewsComment\Domain\Model\Rating> $rating
     */
    public function getRating()
    {
        return $this->rating;
    }
    
    /**
     * Sets the rating
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DRCSystems\NewsComment\Domain\Model\Rating> $rating
     * @return void
     */
    public function setRating(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $rating)
    {
        $this->rating = $rating;
    }
    
    /**
     * Returns the crdate
     *
     * @return int $crdate
     */
    public function getCrdate()
    {
        return $this->crdate;
    }
    
    /**
     * Sets the crdate
     *
     * @param int $crdate
     * @return void
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;
    }
    
    /**
     * Returns the votes
     *
     * @return int $votes
     */
    public function getVotes()
    {
        return $this->votes;
    }
    
    /**
     * Sets the votes
     *
     * @param int $votes
     * @return void
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;
    }
    
    /**
     * Returns the hidden
     *
     * @return bool $hidden
     */
    public function getHidden()
    {
        return $this->hidden;
    }
    
    /**
     * Sets the hidden
     *
     * @param bool $hidden
     * @return void
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * Returns the deleted
     *
     * @return bool $deleted
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
    
    /**
     * Sets the deleted
     *
     * @param bool $deleted
     * @return void
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }
    
    /**
     * Returns the rate
     *
     * @return float rate
     */
    public function getRate()
    {
        return $this->rate;
    }
    
    /**
     * Sets the rate
     *
     * @param string $rate
     * @return void
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }
    
    /**
     * Returns the allowrating
     *
     * @return int $allowrating
     */
    public function getAllowrating()
    {
        return $this->allowrating;
    }
    
    /**
     * Sets the allowrating
     *
     * @param int $allowrating
     * @return void
     */
    public function setAllowrating($allowrating)
    {
        $this->allowrating = $allowrating;
    }
    
    /**
     * Returns the ipaddress
     *
     * @return string $ipaddress
     */
    public function getIpaddress()
    {
        return $this->ipaddress;
    }
    
    /**
     * Sets the ipaddress
     *
     * @param string $ipaddress
     * @return void
     */
    public function setIpaddress($ipaddress)
    {
        $this->ipaddress = $ipaddress;
    }
    
    /**
     * Returns the spam
     *
     * @return bool $spam
     */
    public function getSpam()
    {
        return $this->spam;
    }
    
    /**
     * Sets the spam
     *
     * @param bool $spam
     * @return void
     */
    public function setSpam($spam)
    {
        $this->spam = $spam;
    }
    
    /**
     * Returns the boolean state of spam
     *
     * @return bool
     */
    public function isSpam()
    {
        return $this->spam;
    }
    
    /**
     * Returns the website
     *
     * @return string $website
     */
    public function getWebsite()
    {
        return $this->website;
    }
    
    /**
     * Sets the website
     *
     * @param string $website
     * @return void
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }
    
    /**
     * Adds a Comment
     *
     * @param \DRCSystems\NewsComment\Domain\Model\Comment $parentcomment
     * @return void
     */
    public function addParentcomment(\DRCSystems\NewsComment\Domain\Model\Comment $parentcomment)
    {
        $this->parentcomment->attach($parentcomment);
    }
    
    /**
     * Removes a Comment
     *
     * @param \DRCSystems\NewsComment\Domain\Model\Comment $parentcommentToRemove The Comment to be removed
     * @return void
     */
    public function removeParentcomment(\DRCSystems\NewsComment\Domain\Model\Comment $parentcommentToRemove)
    {
        $this->parentcomment->detach($parentcommentToRemove);
    }
    
    /**
     * Returns the parentcomment
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DRCSystems\NewsComment\Domain\Model\Comment> $parentcomment
     */
    public function getParentcomment()
    {
        return $this->parentcomment;
    }
    
    /**
     * Sets the parentcomment
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DRCSystems\NewsComment\Domain\Model\Comment> $parentcomment
     * @return void
     */
    public function setParentcomment(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $parentcomment)
    {
        $this->parentcomment = $parentcomment;
    }

    /**
     * Returns the newstitle
     *
     * @return string $newstitle
     */
    public function getNewstitle()
    {
        return $this->newstitle;
    }
    
    /**
     * Sets the newstitle
     *
     * @param string $newstitle
     * @return void
     */
    public function setNewstitle($newstitle)
    {
        $this->newstitle = $newstitle;
    }

    /**
     * Returns the uid
     *
     * @return int $uid
     */
    public function getUid()
    {
        return $this->uid;
    }
    
    /**
     * Sets the uid
     *
     * @param int $uid
     * @return void
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * Returns the totalcomments
     *
     * @return int $totalcomments
     */
    public function getTotalcomments()
    {
        return $this->totalcomments;
    }
    
    /**
     * Sets the totalcomments
     *
     * @param int $totalcomments
     * @return void
     */
    public function setTotalcomments($totalcomments)
    {
        $this->totalcomments = $totalcomments;
    }

    /**
     * Returns the pageid
     *
     * @return int $pageid
     */
    public function getPageid()
    {
        return $this->pageid;
    }
    
    /**
     * Sets the pageid
     *
     * @param int $pageid
     * @return void
     */
    public function setPageid($pageid)
    {
        $this->pageid = $pageid;
    }

    /**
     * Returns the posturl
     *
     * @return string $posturl
     */
    public function getPosturl()
    {
        return $this->posturl;
    }
    
    /**
     * Sets the posturl
     *
     * @param string $posturl
     * @return void
     */
    public function setPosturl($posturl)
    {
        $this->posturl = $posturl;
    }


    /**
     * Get the news is hidden or deleted
     *
     * @return bool $isNewsHiddenOrDeleted
     */
    public function getIsNewsHiddenOrDeleted()
    {
        return $this->isNewsHiddenOrDeleted;
    }

    /**
     * Set the news is hidden or deleted
     *
     * @param bool $newsHiddenOrDeleted
     */
    public function setIsNewsHiddenOrDeleted($newsHiddenOrDeleted)
    {
        $this->isNewsHiddenOrDeleted = $newsHiddenOrDeleted;
    }
}