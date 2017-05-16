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
 * Rating
 */
class Rating extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * comment
     *
     * @var int
     */
    protected $comment = 0;
    
    /**
     * rate
     *
     * @var int
     */
    protected $rate = 0;
    
    /**
     * usersession
     *
     * @var string
     */
    protected $usersession = '';
    
    /**
     * user
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    protected $user = null;
    
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
     * Returns the rate
     *
     * @return int $rate
     */
    public function getRate()
    {
        return $this->rate;
    }
    
    /**
     * Sets the rate
     *
     * @param int $rate
     * @return void
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }
    
    /**
     * Returns the comment
     *
     * @return int $comment
     */
    public function getComment()
    {
        return $this->comment;
    }
    
    /**
     * Sets the comment
     *
     * @param int $comment
     * @return void
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }
}
