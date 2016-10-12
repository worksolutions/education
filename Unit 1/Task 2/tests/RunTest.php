<?php

/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */
class RunTest extends PHPUnit_Framework_TestCase {

    /**
     * @return \WS\Education\Unit1\Task2\Client
     */
    private function getClient() {
        return new \WS\Education\Unit1\Task2\Client("127.0.0.1", 10100, 1);
    }

    public function testNotEmpty() {
        $client = $this->getClient();
        $client->send(4);

        $this->assertNotEmpty($client->receive());
    }

    public function testEqualString() {
        $client = $this->getClient();
        $client->send(6);

        $this->assertEquals("Все существующие на рынке рейтинги проводят ранжирование в рамках локальных сегментов рынка интерактивных коммуникаций.\n", $client->receive());
    }
    public function testEqualStringOverLimit() {
        $client = $this->getClient();
        $client->send(15);

        $this->assertEquals("— Золотой Сотни российского Digital!\n", $client->receive());
    }
}
