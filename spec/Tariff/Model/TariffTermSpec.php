<?php

namespace spec\App\Tariff\Model;

use App\Tariff\Model\TariffTerm;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;

class TariffTermSpec extends ObjectBehavior
{
    function it_is_start_before_end()
    {
        $from = new DateTimeImmutable('now');
        $to = new DateTimeImmutable('tomorrow');
        $this->beConstructedWith($from, $to);

        $this
            ->shouldNotThrow()
            ->duringInstantiation()
        ;
    }

    function it_is_includes_date_between_start_and_end()
    {
        $start = new DateTimeImmutable('now');
        $end = new DateTimeImmutable('tomorrow');
        $oneHourAgoFromStart = $start->modify('+1 hour');
        $this->beConstructedWith($start, $end);

        $this
            ->includes($oneHourAgoFromStart)
            ->shouldReturn(true)
        ;
    }

    function it_is_includes_lower_bound_date()
    {
        $start = new DateTimeImmutable('now');
        $end = new DateTimeImmutable('tomorrow');
        $this->beConstructedWith($start, $end);

        $this
            ->includes($start)
            ->shouldReturn(true)
        ;
    }

    function it_is_not_includes_higher_bound_date()
    {
        $start = new DateTimeImmutable('now');
        $end = new DateTimeImmutable('tomorrow');
        $this->beConstructedWith($start, $end);

        $this
            ->includes($end)
            ->shouldReturn(false)
        ;
    }

    function it_is_intersects_if_includes_higher_bound_of_term()
    {
        $start = new DateTimeImmutable('now');
        $end = new DateTimeImmutable('tomorrow');
        $term = new TariffTerm($start->modify('-1 hour'), $end->modify('-1 hour'));
        $this->beConstructedWith($start, $end);

        $this
            ->intersects($term)
            ->shouldReturn(true)
        ;
    }

    function it_is_intersects_if_includes_lower_bound_of_term()
    {
        $start = new DateTimeImmutable('now');
        $end = new DateTimeImmutable('tomorrow');
        $term = new TariffTerm($start->modify('+1 hour'), $end->modify('+1 hour'));
        $this->beConstructedWith($start, $end);

        $this
            ->intersects($term)
            ->shouldReturn(true)
        ;
    }

    function it_is_intersects_if_terms_equals()
    {
        $start = new DateTimeImmutable('now');
        $end = new DateTimeImmutable('tomorrow');
        $term = new TariffTerm($start, $end);
        $this->beConstructedWith($start, $end);

        $this
            ->intersects($term)
            ->shouldReturn(true)
        ;
    }
}
