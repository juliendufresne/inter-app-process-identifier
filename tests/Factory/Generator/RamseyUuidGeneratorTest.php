<?php

declare(strict_types=1);

namespace {
    $mockClassExistsFunction = false;
}

namespace JulienDufresne\RequestId\Factory\Generator {
    // override the global class_exists function used in the RamseyUuidGenerator class to test both scenario
    function class_exists($className)
    {
        global $mockClassExistsFunction;

        if (isset($mockClassExistsFunction) && $mockClassExistsFunction) {
            return false;
        }

        return call_user_func('\class_exists', $className);
    }
}

namespace JulienDufresne\RequestId\Tests\Factory\Generator {
    use JulienDufresne\RequestId\Factory\Generator\RamseyUuidGenerator;
    use PHPUnit\Framework\TestCase;

    /**
     * @covers \JulienDufresne\RequestId\Factory\Generator\RamseyUuidGenerator
     */
    final class RamseyUuidGeneratorTest extends TestCase
    {
        public function setUp()
        {
            global $mockClassExistsFunction;

            $mockClassExistsFunction = false;
        }

        public function testGenerateUniqueIdentifier(): void
        {
            $object = new RamseyUuidGenerator();

            $result1 = $object->generateUniqueIdentifier();
            $result2 = $object->generateUniqueIdentifier();

            $this->assertNotSame($result1, $result2);
        }

        public function testGenerateUniqueIdentifierWhenPackageDoesNotExists(): void
        {
            global $mockClassExistsFunction;
            $mockClassExistsFunction = true;

            $object = new RamseyUuidGenerator();
            $this->expectException(\LogicException::class);

            $object->generateUniqueIdentifier();
        }
    }
}
