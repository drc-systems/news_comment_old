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

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * The repository for Comments
 */
class CommentRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    const SORT_ORDER_ASC = 'asc';
    const SORT_ORDER_DESC = 'desc';

    protected $defaultOrderings = array(
        'crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
    );
    
    /**
     * Get Comments By News
     *
     * @param int $newsId $newsId
     * @param array $filter filter
     *
     * @return mixed
     */
    public function getCommentsByNews($newsId, $filter = array())
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $queryArr = array();
        $queryArr = array(
            $query->equals('newsuid', $newsId),
            $query->equals('comment', 0),
            $query->equals('spam', 0),
        );
        if ($filter['searchterm'] != '') {
            $filter['searchterm'] = filter_var(trim($filter['searchterm']), FILTER_SANITIZE_STRING);
            array_push($queryArr, $query->like('description', '%' . $filter['searchterm'] . '%'));
        }

        $query->matching($query->logicalAnd($queryArr));
        if ($filter['sort'] != '') {
            $sort = intval($filter['sort']);
        }
        if ($sort == 1) {
            $query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        } else {
            if ($sort == 2) {
                $query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
            } else {
                if ($sort == 3) {
                    $query->setOrderings(
                        array('rate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING)
                    );
                } else {
                    if ($sort == 4) {
                        $query->setOrderings(
                            array('rate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING)
                        );
                    }
                }
            }
        }
        return $query->execute();
    }


    /**
     * Get Backend Comments
     *
     * @param array $filter filter
     * @param bool $isCount isCount
     *
     * @return mixed
     */
    public function getBackendComments($filter = array(), $isCount = false)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $queryArr = array();
        
        if (intval($filter['type'])) {
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
        } else {
            array_push($queryArr, $query->equals('spam', 0));
            array_push($queryArr, $query->equals('deleted', 0));
        }

        if ($filter['searchterm'] != '') {
            $filter['searchterm'] = filter_var(trim($filter['searchterm']), FILTER_SANITIZE_STRING);
            array_push($queryArr, $query->like('description', '%' . $filter['searchterm'] . '%'));
        }

        if ($filter['newsId'] != '') {
            array_push($queryArr, $query->equals('newsuid', intval($filter['newsId'])));
        }

        $query->matching($query->logicalAnd($queryArr));
        if ($filter['sort'] != '') {
            $sort = filter_var(trim($filter['sort']), FILTER_SANITIZE_STRING);
            $order = filter_var(trim($filter['order']), FILTER_SANITIZE_STRING);
        }
        if ($sort == 'date' && $order == self::SORT_ORDER_DESC) {
            $query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        } elseif ($sort == 'date' && $order == self::SORT_ORDER_ASC) {
            $query->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        } elseif ($sort == 'user' && $order == self::SORT_ORDER_DESC) {
            $query->setOrderings(array('username' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        } elseif ($sort == 'user' && $order == self::SORT_ORDER_ASC) {
            $query->setOrderings(array('username' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        } elseif ($sort == 'comment' && $order == self::SORT_ORDER_DESC) {
            $query->setOrderings(
                array('description' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING)
            );
        } elseif ($sort == 'comment' && $order == self::SORT_ORDER_ASC) {
            $query->setOrderings(
                array('description' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING)
            );
        }

        if ($isCount) {
            return $query->count();
        } else {
            return $query->execute();
        }
    }

    /**
     * Get Comment By Id
     *
     * @param int $commentId commentId
     *
     * @return mixed
     */
    public function getCommentById($commentId)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching($query->equals('uid', $commentId));
        $result = $query->execute()->getFirst();
        return $result;
    }

    /**
     * Get Comment By News
     *
     * @param int $newsId newsId
     *
     * @return mixed
     */
    public function getCommentByNews($newsId)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching($query->equals('newsuid', $newsId));
        $result = $query->execute();
        return $result;
    }
}
