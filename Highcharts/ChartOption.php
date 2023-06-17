<?php

namespace Ob\HighchartsBundle\Highcharts;

/**
 * This class is part of the Ob/HighchartsBundle
 * See Highcharts documentation at http://www.highcharts.com/ref/#chart
 */
class ChartOption
{
    private string $option_name;

    public function __construct(string $name)
    {
        $this->option_name = $name;
        $this->{$name} = new \stdClass();
    }

    public function __call(string $name, array $value): self
    {
        $option_name = $this->option_name;
        $this->{$option_name}->{$name} = $value[0];

        return $this;
    }

    public function __get(string $name): string
    {
        $option_name = $this->option_name;
        return $this->{$option_name}->{$name};
    }

    public function __isset(string $name): bool
    {
        $option_name = $this->option_name;
        
        return isset($this->{$option_name}->{$name});
    }
}
