<?php
declare(strict_types=1);

namespace Test\Adapter\MeetupOrganizing\Infrastructure\Database;

use Generator;
use MeetupOrganizing\Domain\Model\Common\Country;
use MeetupOrganizing\Domain\Model\Common\DateAndTime;
use MeetupOrganizing\Domain\Model\Meetup\Meetup;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;
use MeetupOrganizing\Domain\Model\User\UserId;
use MeetupOrganizing\Infrastructure\ContainerConfiguration;
use PHPUnit\Framework\TestCase;
use Test\Adapter\MeetupOrganizing\Infrastructure\OutputAdapterTestServiceContainer;

final class MeetupRepositoryUsingDbalTest extends TestCase
{
    private MeetupRepository $repository;

    private function assertSavedEntityEqualsRetrievedEntity(Meetup $meetup): void
    {
        // Release recorded events, or they get in the way of object comparison:
        $meetup->releaseEvents();

        $this->repository->save($meetup);

        $fromDatabase = $this->repository->getById($meetup->meetupId());

        self::assertEquals($meetup, $fromDatabase);
    }

    protected function setUp(): void
    {
        $container = new OutputAdapterTestServiceContainer(ContainerConfiguration::createForOutputAdapterTesting());
        $this->repository = $container->meetupRepository();
    }

    /**
     * @test
     * @dataProvider entityTransformationsProvider
     * @param array<MeetupMutator> $mutators
     */
    public function save_and_getById(Meetup $meetup, array $mutators): void
    {
        $this->assertSavedEntityEqualsRetrievedEntity($meetup);

        foreach ($mutators as $mutator) {
            $meetup = $mutator->mutate($meetup);

            $this->assertSavedEntityEqualsRetrievedEntity($meetup);
        }
    }

    /**
     * @return Generator<array{Meetup, array<MeetupMutator>}>
     */
    public function entityTransformationsProvider(): Generator
    {
        $meetup = $this->aMeetup();
        yield [
            $meetup,
            [
                new class implements MeetupMutator {
                    public function mutate(Meetup $meetup): Meetup
                    {
                        return $meetup->changeTitle('The new title');
                    }
                }
            ]
        ];

        $meetup = $this->aMeetup();
        yield [
            $meetup,
            [
                new class implements MeetupMutator {
                    public function mutate(Meetup $meetup): Meetup
                    {
                        return $meetup->withRsvp(UserId::fromString('0e1b4c24-8c54-4a5a-8d0a-f04a379062ee'));
                    }
                }
            ]
        ];
    }

    protected function aMeetup(): Meetup
    {
        return Meetup::schedule(
            MeetupId::fromString('d737d359-f323-4ea3-94ca-ded4d0be7984'),
            UserId::fromString('66619860-6ea0-461c-ab40-5dd985f477e9'),
            Country::fromString('NL'),
            'Decoupling from infrastructure',
            'Should be interesting',
            DateAndTime::fromString('2021-10-20T20:00')
        );
    }
}
