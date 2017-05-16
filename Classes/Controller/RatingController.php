<?php
namespace DRCSystems\NewsComment\Controller;

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
 * RatingController
 */
class RatingController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * ratingRepository
     *
     * @var \DRCSystems\NewsComment\Domain\Repository\RatingRepository
     * @inject
     */
    protected $ratingRepository = NULL;
    
    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $ratings = $this->ratingRepository->findAll();
        $this->view->assign('ratings', $ratings);
    }
    
    /**
     * action show
     *
     * @param \DRCSystems\NewsComment\Domain\Model\Rating $rating
     * @return void
     */
    public function showAction(\DRCSystems\NewsComment\Domain\Model\Rating $rating)
    {
        $this->view->assign('rating', $rating);
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
     * action create
     *
     * @param \DRCSystems\NewsComment\Domain\Model\Rating $newRating
     * @return void
     */
    public function createAction(\DRCSystems\NewsComment\Domain\Model\Rating $newRating)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->ratingRepository->add($newRating);
        $this->redirect('list');
    }
    
    /**
     * action edit
     *
     * @param \DRCSystems\NewsComment\Domain\Model\Rating $rating
     * @ignorevalidation $rating
     * @return void
     */
    public function editAction(\DRCSystems\NewsComment\Domain\Model\Rating $rating)
    {
        $this->view->assign('rating', $rating);
    }
    
    /**
     * action update
     *
     * @param \DRCSystems\NewsComment\Domain\Model\Rating $rating
     * @return void
     */
    public function updateAction(\DRCSystems\NewsComment\Domain\Model\Rating $rating)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->ratingRepository->update($rating);
        $this->redirect('list');
    }
    
    /**
     * action delete
     *
     * @param \DRCSystems\NewsComment\Domain\Model\Rating $rating
     * @return void
     */
    public function deleteAction(\DRCSystems\NewsComment\Domain\Model\Rating $rating)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->ratingRepository->remove($rating);
        $this->redirect('list');
    }

}