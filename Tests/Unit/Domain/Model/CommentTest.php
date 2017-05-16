<?php

namespace DRCSystems\NewsComment\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 
 *
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
 * Test case for class \DRCSystems\NewsComment\Domain\Model\Comment.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class CommentTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \DRCSystems\NewsComment\Domain\Model\Comment
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = new \DRCSystems\NewsComment\Domain\Model\Comment();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getNewsuidReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setNewsuidForIntSetsNewsuid()
	{	}

	/**
	 * @test
	 */
	public function getUsernameReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getUsername()
		);
	}

	/**
	 * @test
	 */
	public function setUsernameForStringSetsUsername()
	{
		$this->subject->setUsername('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'username',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getUsermailReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getUsermail()
		);
	}

	/**
	 * @test
	 */
	public function setUsermailForStringSetsUsermail()
	{
		$this->subject->setUsermail('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'usermail',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getUsersessionReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getUsersession()
		);
	}

	/**
	 * @test
	 */
	public function setUsersessionForStringSetsUsersession()
	{
		$this->subject->setUsersession('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'usersession',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getDescriptionReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getDescription()
		);
	}

	/**
	 * @test
	 */
	public function setDescriptionForStringSetsDescription()
	{
		$this->subject->setDescription('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'description',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getRateReturnsInitialValueForFloat()
	{
		$this->assertSame(
			0.0,
			$this->subject->getRate()
		);
	}

	/**
	 * @test
	 */
	public function setRateForFloatSetsRate()
	{
		$this->subject->setRate(3.14159265);

		$this->assertAttributeEquals(
			3.14159265,
			'rate',
			$this->subject,
			'',
			0.000000001
		);
	}

	/**
	 * @test
	 */
	public function getIpaddressReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getIpaddress()
		);
	}

	/**
	 * @test
	 */
	public function setIpaddressForStringSetsIpaddress()
	{
		$this->subject->setIpaddress('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'ipaddress',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getSpamReturnsInitialValueForBool()
	{
		$this->assertSame(
			FALSE,
			$this->subject->getSpam()
		);
	}

	/**
	 * @test
	 */
	public function setSpamForBoolSetsSpam()
	{
		$this->subject->setSpam(TRUE);

		$this->assertAttributeEquals(
			TRUE,
			'spam',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getWebsiteReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getWebsite()
		);
	}

	/**
	 * @test
	 */
	public function setWebsiteForStringSetsWebsite()
	{
		$this->subject->setWebsite('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'website',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getUserReturnsInitialValueForFrontendUser()
	{	}

	/**
	 * @test
	 */
	public function setUserForFrontendUserSetsUser()
	{	}

	/**
	 * @test
	 */
	public function getRatingReturnsInitialValueForRating()
	{
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getRating()
		);
	}

	/**
	 * @test
	 */
	public function setRatingForObjectStorageContainingRatingSetsRating()
	{
		$rating = new \DRCSystems\NewsComment\Domain\Model\Rating();
		$objectStorageHoldingExactlyOneRating = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneRating->attach($rating);
		$this->subject->setRating($objectStorageHoldingExactlyOneRating);

		$this->assertAttributeEquals(
			$objectStorageHoldingExactlyOneRating,
			'rating',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function addRatingToObjectStorageHoldingRating()
	{
		$rating = new \DRCSystems\NewsComment\Domain\Model\Rating();
		$ratingObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$ratingObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($rating));
		$this->inject($this->subject, 'rating', $ratingObjectStorageMock);

		$this->subject->addRating($rating);
	}

	/**
	 * @test
	 */
	public function removeRatingFromObjectStorageHoldingRating()
	{
		$rating = new \DRCSystems\NewsComment\Domain\Model\Rating();
		$ratingObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$ratingObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($rating));
		$this->inject($this->subject, 'rating', $ratingObjectStorageMock);

		$this->subject->removeRating($rating);

	}

	/**
	 * @test
	 */
	public function getParentcommentReturnsInitialValueForComment()
	{
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getParentcomment()
		);
	}

	/**
	 * @test
	 */
	public function setParentcommentForObjectStorageContainingCommentSetsParentcomment()
	{
		$parentcomment = new \DRCSystems\NewsComment\Domain\Model\Comment();
		$objectStorageHoldingExactlyOneParentcomment = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneParentcomment->attach($parentcomment);
		$this->subject->setParentcomment($objectStorageHoldingExactlyOneParentcomment);

		$this->assertAttributeEquals(
			$objectStorageHoldingExactlyOneParentcomment,
			'parentcomment',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function addParentcommentToObjectStorageHoldingParentcomment()
	{
		$parentcomment = new \DRCSystems\NewsComment\Domain\Model\Comment();
		$parentcommentObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$parentcommentObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($parentcomment));
		$this->inject($this->subject, 'parentcomment', $parentcommentObjectStorageMock);

		$this->subject->addParentcomment($parentcomment);
	}

	/**
	 * @test
	 */
	public function removeParentcommentFromObjectStorageHoldingParentcomment()
	{
		$parentcomment = new \DRCSystems\NewsComment\Domain\Model\Comment();
		$parentcommentObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$parentcommentObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($parentcomment));
		$this->inject($this->subject, 'parentcomment', $parentcommentObjectStorageMock);

		$this->subject->removeParentcomment($parentcomment);

	}
}
