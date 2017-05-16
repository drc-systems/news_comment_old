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
 * Test case for class \DRCSystems\NewsComment\Domain\Model\Rating.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class RatingTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \DRCSystems\NewsComment\Domain\Model\Rating
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = new \DRCSystems\NewsComment\Domain\Model\Rating();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getRateReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setRateForIntSetsRate()
	{	}

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
	public function getUserReturnsInitialValueForFrontendUser()
	{	}

	/**
	 * @test
	 */
	public function setUserForFrontendUserSetsUser()
	{	}
}
