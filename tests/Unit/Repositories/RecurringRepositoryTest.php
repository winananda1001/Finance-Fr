<?php

namespace Tests\Unit\Repositories;

use App\Models\Recurring;
use App\Models\Space;
use App\Repositories\RecurringRepository;
use Tests\TestCase;

// TODO
// I don't like this test. While it covers most use-cases (I think), the code written in this test is
// not elegant. I'd like to refactor it at some point.

class RecurringRepositoryTest extends TestCase
{
    private $recurringRepository;
    private $space;

    public function setUp(): void
    {
        parent::setUp();

        $this->recurringRepository = new RecurringRepository();
        $this->space = factory(Space::class)->create(); // Instantiate it here, for reusability
    }

    public function testCreation(): void
    {
        $spaceId = 1;
        $tagId = 1;

        $recurring = $this->recurringRepository->create($spaceId, 'spending', 'monthly', 3, null, $tagId, 'Foo', 10000);

        $this->assertNotNull($recurring);
        $this->assertEquals(10000, $recurring->amount);
    }

    public function testDueYearly(): void
    {
        // Assert that recurring (that has never been used before) is due
        $neverUsedRecurring = factory(Recurring::class)->create([
            'space_id' => $this->space->id,
            'type' => 'earning',
            'interval' => 'yearly',
            'last_used_on' => null
        ]);

        $recurringsDueYearly = $this->recurringRepository->getDueYearly();

        $contains = false;
        foreach ($recurringsDueYearly as $recurring) {
            if ($recurring->id === $neverUsedRecurring->id) {
                $contains = true;
            }
        }

        $this->assertTrue($contains);

        // Assert that recurring (that was last used a year ago) is due
        $yearAgoRecurring = factory(Recurring::class)->create([
            'space_id' => $this->space->id,
            'type' => 'earning',
            'interval' => 'yearly',
            'last_used_on' => date('Y-m-d', strtotime('-1 year'))
        ]);

        $recurringsDueYearly = $this->recurringRepository->getDueYearly();

        $contains = false;
        foreach ($recurringsDueYearly as $recurring) {
            if ($recurring->id === $yearAgoRecurring->id) {
                $contains = true;
            }
        }

        $this->assertTrue($contains);

        // Assert that recurring (that was last used less than a year ago) is not due
        $lessThanYearAgoRecurring = factory(Recurring::class)->create([
            'space_id' => $this->space->id,
            'type' => 'earning',
            'interval' => 'yearly',
            'last_used_on' => date('Y-m-d', strtotime('-364 days'))
        ]);

        $recurringsDueYearly = $this->recurringRepository->getDueYearly();

        $contains = false;
        foreach ($recurringsDueYearly as $recurring) {
            if ($recurring->id === $lessThanYearAgoRecurring->id) {
                $contains = true;
            }
        }

        $this->assertFalse($contains);
    }

    public function testDueMonthly(): void
    {
        $todayDayOfMonth = 23;

        // Assert that recurring (that has never been used before) is due
        $neverUsedRecurring = factory(Recurring::class)->create([
            'space_id' => $this->space->id,
            'type' => 'earning',
            'interval' => 'monthly',
            'day' => $todayDayOfMonth
        ]);

        $recurringsDueMonthly = $this->recurringRepository->getDueMonthly($todayDayOfMonth);

        $contains = false;
        foreach ($recurringsDueMonthly as $recurring) {
            if ($recurring->id === $neverUsedRecurring->id) {
                $contains = true;
            }
        }

        $this->assertTrue($contains);

        // Assert that recurring (that was last used a month ago, on the same day of the month) is due
        $usedMonthAgoRecurring = factory(Recurring::class)->create([
            'space_id' => $this->space->id,
            'type' => 'earning',
            'interval' => 'monthly',
            'day' => $todayDayOfMonth,
            'last_used_on' => date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m') . '-' . $todayDayOfMonth)))
        ]);

        $recurringsDueMonthly = $this->recurringRepository->getDueMonthly($todayDayOfMonth);

        $contains = false;
        foreach ($recurringsDueMonthly as $recurring) {
            if ($recurring->id === $usedMonthAgoRecurring->id) {
                $contains = true;
            }
        }

        $this->assertTrue($contains);

        // Assert that recurring (that was last used less than a month ago) is not due
        $usedLessThanMonthAgoRecurring = factory(Recurring::class)->create([
            'space_id' => $this->space->id,
            'type' => 'earning',
            'interval' => 'monthly',
            'day' => $todayDayOfMonth,
            'last_used_on' => date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m') . '-' . $todayDayOfMonth)))
        ]);

        $recurringsDueMonthly = $this->recurringRepository->getDueMonthly($todayDayOfMonth);

        $contains = false;
        foreach ($recurringsDueMonthly as $recurring) {
            if ($recurring->id === $usedLessThanMonthAgoRecurring->id) {
                $contains = true;
            }
        }

        $this->assertFalse($contains);
    }

    public function testDueBiweekly(): void
    {
        // Assert that recurring (that has never been used before) is due
        $neverUsedRecurring = factory(Recurring::class)->create([
            'space_id' => $this->space->id,
            'type' => 'earning',
            'interval' => 'biweekly',
            'last_used_on' => null
        ]);

        $recurringsDueBiweekly = $this->recurringRepository->getDueBiweekly();

        $contains = false;
        foreach ($recurringsDueBiweekly as $recurring) {
            if ($recurring->id === $neverUsedRecurring->id) {
                $contains = true;
            }
        }

        $this->assertTrue($contains);

        // Assert that recurring (that was last used a week ago) is not due
        $weekAgoReccuring = factory(Recurring::class)->create([
            'space_id' => $this->space->id,
            'type' => 'earning',
            'interval' => 'biweekly',
            'last_used_on' => date('Y-m-d', strtotime('-1 week'))
        ]);

        $recurringsDueBiweekly = $this->recurringRepository->getDueBiweekly();

        $contains = false;
        foreach ($recurringsDueBiweekly as $recurring) {
            if ($recurring->id === $weekAgoReccuring->id) {
                $contains = true;
            }
        }

        $this->assertFalse($contains);

        // Assert that recurring (that was last used two weeks ago) is due
        $twoWeeksAgoReccuring = factory(Recurring::class)->create([
            'space_id' => $this->space->id,
            'type' => 'earning',
            'interval' => 'biweekly',
            'last_used_on' => date('Y-m-d', strtotime('-2 weeks'))
        ]);

        $recurringsDueBiweekly = $this->recurringRepository->getDueBiweekly();

        $contains = false;
        foreach ($recurringsDueBiweekly as $recurring) {
            if ($recurring->id === $twoWeeksAgoReccuring->id) {
                $contains = true;
            }
        }

        $this->assertTrue($contains);

        // Assert that recurring (that was last used less than 2 weeks ago) is not due
        $lessThanTwoWeeksAgoReccuring = factory(Recurring::class)->create([
            'space_id' => $this->space->id,
            'type' => 'earning',
            'interval' => 'biweekly',
            'last_used_on' => date('Y-m-d', strtotime('-13 days'))
        ]);

        $recurringsDueWeekly = $this->recurringRepository->getDueBiweekly();

        $contains = false;
        foreach ($recurringsDueWeekly as $recurring) {
            if ($recurring->id === $lessThanTwoWeeksAgoReccuring->id) {
                $contains = true;
            }
        }

        $this->assertFalse($contains);
    }

    public function testDueWeekly(): void
    {
        // Assert that recurring (that has never been used before) is due
        $neverUsedRecurring = factory(Recurring::class)->create([
            'space_id' => $this->space->id,
            'type' => 'earning',
            'interval' => 'weekly',
            'last_used_on' => null
        ]);

        $recurringsDueWeekly = $this->recurringRepository->getDueWeekly();

        $contains = false;
        foreach ($recurringsDueWeekly as $recurring) {
            if ($recurring->id === $neverUsedRecurring->id) {
                $contains = true;
            }
        }

        $this->assertTrue($contains);

        // Assert that recurring (that was last used a week ago) is due
        $weekAgoReccuring = factory(Recurring::class)->create([
            'space_id' => $this->space->id,
            'type' => 'earning',
            'interval' => 'weekly',
            'last_used_on' => date('Y-m-d', strtotime('-1 week'))
        ]);

        $recurringsDueWeekly = $this->recurringRepository->getDueWeekly();

        $contains = false;
        foreach ($recurringsDueWeekly as $recurring) {
            if ($recurring->id === $weekAgoReccuring->id) {
                $contains = true;
            }
        }

        $this->assertTrue($contains);

        // Assert that recurring (that was last used less than a week ago) is not due
        $lessThanWeekAgoReccuring = factory(Recurring::class)->create([
            'space_id' => $this->space->id,
            'type' => 'earning',
            'interval' => 'weekly',
            'last_used_on' => date('Y-m-d', strtotime('-6 days'))
        ]);

        $recurringsDueWeekly = $this->recurringRepository->getDueWeekly();

        $contains = false;
        foreach ($recurringsDueWeekly as $recurring) {
            if ($recurring->id === $lessThanWeekAgoReccuring->id) {
                $contains = true;
            }
        }

        $this->assertFalse($contains);
    }

    public function testDueDaily(): void
    {
        // Assert that recurring (that has never been used before) is due
        $neverUsedRecurring = factory(Recurring::class)->create([
            'space_id' => $this->space->id,
            'type' => 'earning',
            'interval' => 'daily',
            'last_used_on' => null
        ]);

        $recurringsDueDaily = $this->recurringRepository->getDueDaily();

        $contains = false;
        foreach ($recurringsDueDaily as $recurring) {
            if ($recurring->id === $neverUsedRecurring->id) {
                $contains = true;
            }
        }

        $this->assertTrue($contains);

        // Assert that recurring (that has already been used today) is not due
        $this->recurringRepository->update($neverUsedRecurring->id, [
            'last_used_on' => date('Y-m-d')
        ]);

        $recurringsDueDaily = $this->recurringRepository->getDueDaily();

        $contains = false;
        foreach ($recurringsDueDaily as $recurring) {
            if ($recurring->id === $neverUsedRecurring->id) {
                $contains = true;
            }
        }

        $this->assertFalse($contains);
    }
}
