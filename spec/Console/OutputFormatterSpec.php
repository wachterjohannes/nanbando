<?php

namespace spec\Nanbando\Console;

use Nanbando\Console\OutputFormatter;
use Nanbando\Console\SectionOutputFormatter;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;

class OutputFormatterSpec extends ObjectBehavior
{
    public function let(
        ConsoleOutput $output
    ) {
        $this->beConstructedWith($output);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(OutputFormatter::class);
    }

    public function it_should_display_headline(
        ConsoleOutput $output
    ) {
        $this->headline('This is a test');

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('This is a test')->shouldBeCalled();
        $output->writeln('==============')->shouldBeCalled();
    }

    public function it_should_display_headline_and_format_dates(
        ConsoleOutput $output
    ) {
        $this->headline('%s %s', new \DateTime('2018-01-01T01:01:01'), new \DateTimeImmutable('2018-01-01T01:01:01'));

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('2018-01-01 01:01:01 2018-01-01 01:01:01')->shouldBeCalled();
        $output->writeln('=======================================')->shouldBeCalled();
    }

    public function it_should_display_headline_and_format_booleans(
        ConsoleOutput $output
    ) {
        $this->headline('%s %s', true, false);

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('yes no')->shouldBeCalled();
        $output->writeln('======')->shouldBeCalled();
    }

    public function it_should_display_headline_and_format_arrays(
        ConsoleOutput $output
    ) {
        $this->headline('%s', [1, 2, 3]);

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('1, 2, 3')->shouldBeCalled();
        $output->writeln('=======')->shouldBeCalled();
    }

    public function it_should_display_headline_and_format_strings(
        OutputInterface $output
    ) {
        $this->headline('%s', 'test');

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('"test"')->shouldBeCalled();
        $output->writeln('======')->shouldBeCalled();
    }

    public function it_should_display_info(
        ConsoleOutput $output
    ) {
        $this->info('This is a test');

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('<info>This is a test</info>')->shouldBeCalled();
    }

    public function it_should_display_info_and_format_dates(
        ConsoleOutput $output
    ) {
        $this->info('%s %s', new \DateTime('2018-01-01T01:01:01'), new \DateTimeImmutable('2018-01-01T01:01:01'));

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('<info>2018-01-01 01:01:01 2018-01-01 01:01:01</info>')->shouldBeCalled();
    }

    public function it_should_display_info_and_format_booleans(
        ConsoleOutput $output
    ) {
        $this->info('%s %s', true, false);

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('<info>yes no</info>')->shouldBeCalled();
    }

    public function it_should_display_info_and_format_arrays(
        ConsoleOutput $output
    ) {
        $this->info('%s', [1, 2, 3]);

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('<info>1, 2, 3</info>')->shouldBeCalled();
    }

    public function it_should_display_info_and_format_strings(
        ConsoleOutput $output
    ) {
        $this->info('%s', 'test');

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('<info>"test"</info>')->shouldBeCalled();
    }

    public function it_should_display_warning(
        ConsoleOutput $output
    ) {
        $this->warning('This is a test');

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('<warning>This is a test</warning>')->shouldBeCalled();
    }

    public function it_should_display_warning_and_format_dates(
        ConsoleOutput $output
    ) {
        $this->warning('%s %s', new \DateTime('2018-01-01T01:01:01'), new \DateTimeImmutable('2018-01-01T01:01:01'));

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('<warning>2018-01-01 01:01:01 2018-01-01 01:01:01</warning>')->shouldBeCalled();
    }

    public function it_should_display_warning_and_format_booleans(
        ConsoleOutput $output
    ) {
        $this->warning('%s %s', true, false);

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('<warning>yes no</warning>')->shouldBeCalled();
    }

    public function it_should_display_warning_and_format_arrays(
        ConsoleOutput $output
    ) {
        $this->warning('%s', [1, 2, 3]);

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('<warning>1, 2, 3</warning>')->shouldBeCalled();
    }

    public function it_should_display_warning_and_format_strings(
        ConsoleOutput $output
    ) {
        $this->warning('%s', 'test');

        $output->writeln('')->shouldBeCalledTimes(2);
        $output->writeln('<warning>"test"</warning>')->shouldBeCalled();
    }

    public function it_should_display_list(
        ConsoleOutput $output
    ) {
        $this->list(['key1' => 1, 'key2' => 2, 'key3' => 3]);

        $output->writeln(' * key1: 1')->shouldBeCalled();
        $output->writeln(' * key2: 2')->shouldBeCalled();
        $output->writeln(' * key3: 3')->shouldBeCalled();
    }

    public function it_should_display_list_and_format_data_types(
        ConsoleOutput $output
    ) {
        $this->list(
            [
                'key1' => true,
                'key2' => false,
                'key3' => new \DateTime('2018-01-01T01:01:01'),
                'key4' => new \DateTimeImmutable('2018-01-01T01:01:01'),
                'key5' => [1, 2, 3],
                'key6' => 'test',
            ]
        );

        $output->writeln(' * key1: yes')->shouldBeCalled();
        $output->writeln(' * key2: no')->shouldBeCalled();
        $output->writeln(' * key3: 2018-01-01 01:01:01')->shouldBeCalled();
        $output->writeln(' * key4: 2018-01-01 01:01:01')->shouldBeCalled();
        $output->writeln(' * key5: 1, 2, 3')->shouldBeCalled();
        $output->writeln(' * key6: "test"')->shouldBeCalled();
    }

    public function it_should_start_secion_and_return_formatter(
        ConsoleOutput $output,
        ConsoleSectionOutput $sectionOutput
    ) {
        $output->section()->shouldBeCalled()->willReturn($sectionOutput);

        $this->section()->shouldBeAnInstanceOf(SectionOutputFormatter::class);
    }
}
