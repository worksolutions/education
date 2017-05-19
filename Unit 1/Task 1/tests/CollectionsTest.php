<?php
use WS\Education\Unit1\Task1\Queue;
use WS\Education\Unit1\Task1\Stack;

class Integer {

    private $value = 0;

    /**
     * Integer constructor.
     * @param integer $val
     */
    public function __construct($val) {
        if (!is_integer($val)) {
            throw new InvalidArgumentException;
        }
        $this->value = $val;
    }

    /**
     * @return integer
     */
    public function getValue() {
        return $this->value;
    }
}

/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */
class CollectionsTest extends PHPUnit_Framework_TestCase {

    public function testAddToStack() {
        $stack = new Stack();
        $stack->push(new Integer(1));
        $stack->push(new Integer(2));
        $stack->push(new Integer(3));

        $this->assertEquals(3, $stack->pop()->getValue());
        $this->assertEquals(2, $stack->size());
    }

    public function testRemoveFromStack() {
        $stack = new Stack();
        $stack->push(new Integer(1));
        $stack->push(new Integer(2));
        $stack->push(new Integer(3));
        $stack->push(new Integer(4));

        $stack->pop();

        $this->assertEquals(3, $stack->pop()->getValue());
        $this->assertEquals(2, $stack->size());
    }

    public function testCollectionSizes() {
        $stack = new Stack();
        $stack->push(new Integer(1));
        $stack->push(new Integer(2));
        $stack->push(new Integer(3));
        $stack->push(new Integer(4));

        $queue = new Queue();
        $queue->push(new Integer(1));
        $queue->push(new Integer(1));
        $queue->push(new Integer(1));

        $this->assertEquals(4, $stack->size());
        $this->assertEquals(3, $queue->size());
    }

    public function testAddToQueue() {
        $queue = new Queue();
        $queue->push(new Integer(1));
        $queue->push(new Integer(2));
        $queue->push(new Integer(3));

        $this->assertEquals(1, $queue->pop()->getValue());
    }

    public function testRemoveFromQueue() {
        $queue = new Queue();
        $queue->push(new Integer(1));
        $queue->push(new Integer(2));
        $queue->push(new Integer(3));
        $queue->push(new Integer(4));

        $queue->pop();

        $this->assertEquals(2, $queue->pop()->getValue());
        $this->assertEquals(2, $queue->size());
    }

    public function testNotEqualsCollections() {
        $queue = new Queue();
        $queue->push(new Integer(1));
        $queue->push(new Integer(2));

        $stack = new Stack();
        $stack->push(new Integer(1));
        $stack->push(new Integer(2));

        $this->assertNotEquals($queue->pop()->getValue(), $stack->pop()->getValue());
    }

    public function testAddItemsAnotherTypes() {
        $stack = new Stack(Integer::class);

        try {
            $stack->push(1);
            $this->fail("Need stack exception");
        }
        catch (Exception $e) {
        }

        $queue = new Queue(Integer::class);

        try {
            $queue->push(1);
            $this->fail("Need queue exception");
        }
        catch (Exception $e) {
        }
    }

    public function testTryEmptyGet() {
        $stack = new Stack();
        $stack->push(1);
        $stack->pop();

        try {
            $stack->pop();
            $this->fail("Need exception if try get element from empty collection");
        } catch (Exception $e) {
        }

        $queue = new Queue();
        $queue->push(1);
        $queue->pop();

        try {
            $queue->pop();
            $this->fail("Need exception if try get element from empty collection");
        } catch (Exception $e) {
        }
    }
}
