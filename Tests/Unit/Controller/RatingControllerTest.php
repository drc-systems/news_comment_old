<?php
namespace DRCSystems\NewsComment\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class DRCSystems\NewsComment\Controller\RatingController.
 *
 */
class RatingControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \DRCSystems\NewsComment\Controller\RatingController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('DRCSystems\\NewsComment\\Controller\\RatingController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllRatingsFromRepositoryAndAssignsThemToView()
	{

		$allRatings = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$ratingRepository = $this->getMock('DRCSystems\\NewsComment\\Domain\\Repository\\RatingRepository', array('findAll'), array(), '', FALSE);
		$ratingRepository->expects($this->once())->method('findAll')->will($this->returnValue($allRatings));
		$this->inject($this->subject, 'ratingRepository', $ratingRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('ratings', $allRatings);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenRatingToView()
	{
		$rating = new \DRCSystems\NewsComment\Domain\Model\Rating();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('rating', $rating);

		$this->subject->showAction($rating);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenRatingToRatingRepository()
	{
		$rating = new \DRCSystems\NewsComment\Domain\Model\Rating();

		$ratingRepository = $this->getMock('DRCSystems\\NewsComment\\Domain\\Repository\\RatingRepository', array('add'), array(), '', FALSE);
		$ratingRepository->expects($this->once())->method('add')->with($rating);
		$this->inject($this->subject, 'ratingRepository', $ratingRepository);

		$this->subject->createAction($rating);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenRatingToView()
	{
		$rating = new \DRCSystems\NewsComment\Domain\Model\Rating();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('rating', $rating);

		$this->subject->editAction($rating);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenRatingInRatingRepository()
	{
		$rating = new \DRCSystems\NewsComment\Domain\Model\Rating();

		$ratingRepository = $this->getMock('DRCSystems\\NewsComment\\Domain\\Repository\\RatingRepository', array('update'), array(), '', FALSE);
		$ratingRepository->expects($this->once())->method('update')->with($rating);
		$this->inject($this->subject, 'ratingRepository', $ratingRepository);

		$this->subject->updateAction($rating);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenRatingFromRatingRepository()
	{
		$rating = new \DRCSystems\NewsComment\Domain\Model\Rating();

		$ratingRepository = $this->getMock('DRCSystems\\NewsComment\\Domain\\Repository\\RatingRepository', array('remove'), array(), '', FALSE);
		$ratingRepository->expects($this->once())->method('remove')->with($rating);
		$this->inject($this->subject, 'ratingRepository', $ratingRepository);

		$this->subject->deleteAction($rating);
	}
}
