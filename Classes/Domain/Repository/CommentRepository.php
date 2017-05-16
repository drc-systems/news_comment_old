<?php
namespace DRCSystems\NewsComment\Domain\Repository;

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
 * The repository for Comments
 */
class CommentRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    protected $defaultOrderings = array(
        'crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
    );
    
    /**
     * Userid
     *
     * @param $newsId
     * @param $filter
     */
    public function getCommentsByNews($newsId, $filter = array())
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $queryArr = array();
        $queryArr = array(
            $query->equals('newsuid', $newsId),
            $query->equals('comment', 0),
            $query->equals('spam', 0),
        );
        if ($filter['searchterm'] != '') {
            array_push($queryArr, $query->like('description', '%' . $filter['searchterm'] . '%'));
        }
        $query->matching($query->logicalAnd($queryArr));
        if ($filter['sort'] != '') {
            $sort = $filter['sort'];
        }
        if ($sort == 1) {
            $query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        } else {
            if ($sort == 2) {
                $query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
            } else {
                if ($sort == 3) {
                    $query->setOrderings(array('rate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
                } else {
                    if ($sort == 4) {
                        $query->setOrderings(array('rate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
                    }
                }
            }
        }
        return $query->execute();
    }


    /**
     * Userid
     *
     * @param $newsId
     * @param $filter
     */
    public function getBackendComments($filter = array(),$isCount = 0)
    {
        
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $queryArr = array();
        
        if($filter['type']){
         
            switch ($filter['type']) {
                case 1:
                         array_push($queryArr, $query->equals('deleted', 0));
                         array_push($queryArr, $query->equals('spam', 0));
                         break;
                case 2:
                         array_push($queryArr, $query->equals('deleted', 0));
                         array_push($queryArr, $query->equals('spam', 0));
                         array_push($queryArr, $query->equals('hidden', 1));
                         break;
                case 3:
                         array_push($queryArr, $query->equals('hidden', 0));
                         array_push($queryArr, $query->equals('deleted', 0));
                         array_push($queryArr, $query->equals('spam', 0));
                         break;
                case 4:
                         array_push($queryArr, $query->equals('spam', 1));
                         break;
                case 5:
                         array_push($queryArr, $query->equals('deleted', 1));
                         break;   
                default:
                         array_push($queryArr, $query->equals('deleted', 0));
                         break;        
            }
        }
        else
        {
            array_push($queryArr, $query->equals('spam', 0));
            array_push($queryArr, $query->equals('deleted', 0));
        }

        if ($filter['searchterm'] != '') {
            array_push($queryArr, $query->like('description', '%' . $filter['searchterm'] . '%'));
        }

        if ($filter['newsId'] != '') {
            array_push($queryArr, $query->equals('newsuid', $filter['newsId']));
        }

        $query->matching($query->logicalAnd($queryArr));
        if ($filter['sort'] != '') {
           $sort = $filter['sort'];
           $order = $filter['order'];
        }
        if ($sort == 'date' && $order=='desc')  {
            $query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        } 
        else if ($sort == 'date' && $order=='asc') {
            $query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        }
        else if ($sort == 'user' && $order=='desc') {
            $query->setOrderings(array('username' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        }
        else if ($sort == 'user' && $order=='asc') {
            $query->setOrderings(array('username' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        }
        else if ($sort == 'comment' && $order=='desc') {
            $query->setOrderings(array('description' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        }
        else if ($sort == 'comment' && $order=='asc') {
            $query->setOrderings(array('description' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        } 

        if($isCount == 1)
            return $query->count(); 
        else
            return $query->execute();
    }

    /**
     * Userid
     *
     * @param $commentId
     */
    public function getByCommentid($commentId)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->matching($query->equals('uid', $commentId));
        $result = $query->execute()->getFirst();
        return $result;
    }

    /**
     * Userid
     *
     * @param $commentId
     */
    public function getByNews($newsuid)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->matching($query->equals('newsuid', $newsuid));
        $result = $query->execute();
        return $result;
    }

    

    

}