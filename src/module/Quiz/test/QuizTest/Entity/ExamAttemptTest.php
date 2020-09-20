<?php

namespace QuizTest\Service;


use DateInterval;
use Quiz\Entity\ExamAttempt;
use QuizTest\BaseSetUp;

class ExamAttemptServiceTest extends BaseSetUp {

    public function testIsExpired_withExpirationDateTomorrow_isNotExpired() {
        $today = new \DateTime();
        $tomorrow = $today->add(new DateInterval('P1D'));

        $examAttempt = new ExamAttempt();
        $examAttempt->setExpirationDate($tomorrow);

        $this->assertFalse($examAttempt->isExpired());
    }

    public function testIsExpired_withExpirationDateYesterday_isExpired() {
        $today = new \DateTime();
        $yesterday = $today->sub(new DateInterval('P1D'));

        $examAttempt = new ExamAttempt();
        $examAttempt->setExpirationDate($yesterday);

        $this->assertTrue($examAttempt->isExpired());
    }

    public function testIsExpired_withExpirationDateToday_isNotExpired() {
        $today = new \DateTime();

        $examAttempt = new ExamAttempt();
        $examAttempt->setExpirationDate($today);

        $this->assertFalse($examAttempt->isExpired());
    }

    public function testIsExpired_noExpirationDate_isNotExpired() {
        $examAttempt = new ExamAttempt();
        $this->assertFalse($examAttempt->isExpired());
    }
}