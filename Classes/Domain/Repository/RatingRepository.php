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
 * The repository for Ratings
 */
class RatingRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * Function count rating comment
     *
     * @param int $commentId commentId
     *
     * @return array
     */
    public function countRatingComment($commentId)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching($query->equals('comment', $commentId))->count();
        $queryresult = $query->execute();
        $total_votes = $total_rates = 0;
        foreach ($queryresult as $value) {
            $total_rates = $total_rates + $value->getRate();
            $total_votes = $total_votes + 1;
        }
        $rating = @number_format($total_rates / $total_votes, 2);
        $width = @number_format($total_rates / $total_votes, 2) * 20;
        $arr_ratings = array('rating' => $rating, 'votes' => $total_votes, 'width' => $width);
        return $arr_ratings;
    }


    /**
     * Function get rating by session
     *
     * @param string $sessionId sessionId
     *
     * @return mixed
     */
    public function getRatingBySession($sessionId)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching($query->equals('usersession', $sessionId));
        $result = $query->execute();
        return $result;
    }

    /**
     * Function get rated user
     *
     * @param string $userId userId
     * @param string $commentId commentId
     *
     * @return mixed
     */
    public function getRatedUser($userId, $commentId)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching($query->equals('user', $userId), $query->equals('comment', $commentId));
        $result = $query->execute();
        return $result;
    }
}
