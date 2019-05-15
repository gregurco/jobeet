<?php

namespace App\Tests\Form;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

abstract class AbstractTypeTest extends TypeTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var MockObject|ManagerRegistry
     */
    private $emRegistry;

    /**
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    protected function setUp(): void
    {
        $this->em = DoctrineTestHelper::createTestEntityManager();
        $this->emRegistry = $this->createRegistryMock($this->em);

        parent::setUp();

        $schemaTool = new SchemaTool($this->em);
        $classNames = $this->getEntityClassNames();
        $classData = [];
        foreach ($classNames as $class) {
            $classData[] = $this->em->getClassMetadata($class);
        }
        $schemaTool->dropSchema($classData);
        $schemaTool->createSchema($classData);
    }

    /**
     * @param $em
     *
     * @return MockObject|ManagerRegistry
     */
    protected function createRegistryMock($em): MockObject
    {
        $registry = $this
            ->getMockBuilder(ManagerRegistry::class)
            ->getMock();

        $registry
            ->method('getManagerForClass')
            ->willReturn($em);

        return $registry;
    }

    /**
     * @return array
     */
    protected function getExtensions(): array
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        return array_merge(
            parent::getExtensions(),
            [
                new DoctrineOrmExtension($this->emRegistry),
                new ValidatorExtension($validator),
            ]
        );
    }

    /**
     * Should return FQCN for all used types
     *
     * @return array
     */
    protected function getEntityClassNames(): array
    {
        return [];
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em = null;
        $this->emRegistry = null;
    }
}
