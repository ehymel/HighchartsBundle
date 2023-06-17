<?php

namespace Ob\HighchartsBundle\Highcharts;

use Laminas\Json\Json;

abstract class AbstractChart
{
    // Default options
    public $chart;
    public $colors;
    public $credits;
    public $global;
    public $labels;
    public $lang;
    public $legend;
    public $loading;
    public $plotOptions;
    public $rangeSelector;
    public $point;
    public $series;
    public $drilldown;
    public $subtitle;
    public $symbols;
    public $title;
    public $tooltip;
    public $xAxis;
    public $yAxis;
    public $exporting;
    public $navigation;
    public $pane;
    public $scrollbar;

    public function __construct()
    {
        $chartOptions = array('chart', 'credits', 'global', 'labels', 'lang', 'legend', 'loading', 'plotOptions',
            'rangeSelector', 'point', 'subtitle', 'title', 'tooltip', 'xAxis', 'yAxis', 'pane', 'exporting',
            'navigation', 'drilldown', 'scrollbar');

        foreach ($chartOptions as $option) {
            $this->initChartOption($option);
        }

        $arrayOptions = array('colors', 'series', 'symbols');

        foreach ($arrayOptions as $option) {
            $this->initArrayOption($option);
        }
    }

    abstract public function render();

    public function __call(string $name, mixed $value): self
    {
        $this->$name = $value;

        return $this;
    }

    protected function initChartOption(string $name): void
    {
        $this->{$name} = new ChartOption($name);
    }

    protected function initArrayOption(string $name): void
    {
        $this->{$name} = array();
    }

    protected function renderWithJavascriptCallback(array|ChartOption $chartOption, string $name): string
    {
        $result = "";

        if (gettype($chartOption) === 'array') {
            $result .= $this->renderArrayWithCallback($chartOption, $name);
        }

        if (gettype($chartOption) === 'object') {
            $result .= $this->renderObjectWithCallback($chartOption, $name);
        }

        return $result;
    }

    protected function renderArrayWithCallback(ChartOption $chartOption, string $name): string
    {
        $result = "";

        if (!empty($chartOption)) {
            // Zend\Json is used in place of json_encode to preserve JS anonymous functions
            $result .= $name . ": " . Json::encode($chartOption[0], false, array('enableJsonExprFinder' => true)) . ", \n";
        }

        return $result;
    }

    protected function renderObjectWithCallback(ChartOption $chartOption, string $name): string
    {
        $result = "";

        if (get_object_vars($chartOption)) {
            // Zend\Json is used in place of json_encode to preserve JS anonymous functions
            $result .= $name . ": " . Json::encode($chartOption, false, array('enableJsonExprFinder' => true)) . ",\n";
        }

        return $result;
    }

    protected function renderEngine(string $engine): string
    {
        if ($engine == 'mootools') {
            return 'window.addEvent(\'domready\', function () {';
        } elseif ($engine == 'jquery') {
            return "$(function () {";
        }
        return '';
    }

    protected function renderColors(): string
    {
        if (!empty($this->colors)) {
            return "colors: " . json_encode($this->colors) . ",\n";
        }

        return "";
    }

    protected function renderCredits(): string
    {
        if (get_object_vars($this->credits->credits)) {
            return "credits: " . json_encode($this->credits->credits) . ",\n";
        }

        return "";
    }

    protected function renderSubtitle(): string
    {
        if (get_object_vars($this->subtitle->subtitle)) {
            return "subtitle: " . json_encode($this->subtitle->subtitle) . ",\n";
        }

        return "";
    }

    protected function renderTitle(): string
    {
        if (get_object_vars($this->title->title)) {
            return "title: " . json_encode($this->title->title) . ",\n";
        }

        return "";
    }

    protected function renderXAxis(): string
    {
        if (gettype($this->xAxis) === 'array') {
            return $this->renderWithJavascriptCallback($this->xAxis, "xAxis");
        } elseif (gettype($this->xAxis) === 'object') {
            return $this->renderWithJavascriptCallback($this->xAxis->xAxis, "xAxis");
        }

        return "";
    }

    protected function renderYAxis(): string
    {
        if (gettype($this->yAxis) === 'array') {
            return $this->renderWithJavascriptCallback($this->yAxis, "yAxis");
        } elseif (gettype($this->yAxis) === 'object') {
            return $this->renderWithJavascriptCallback($this->yAxis->yAxis, "yAxis");
        }

        return "";
    }

    protected function renderOptions(): string
    {
        $result = "";

        if (get_object_vars($this->global->global) || get_object_vars($this->lang->lang)) {
            $result .= "\n    Highcharts.setOptions({";
            $result .= $this->renderGlobal();
            $result .= $this->renderLang();
            $result .= "    });\n";
        }

        return $result;
    }

    protected function renderGlobal(): string
    {
        if (get_object_vars($this->global->global)) {
            return "global: " . json_encode($this->global->global) . ",\n";
        }

        return "";
    }

    protected function renderLang(): string
    {
        if (get_object_vars($this->lang->lang)) {
            return "lang: " . json_encode($this->lang->lang) . ",\n";
        }

        return "";
    }

    protected function renderScrollbar(): string
    {
        if (get_object_vars($this->scrollbar->scrollbar)) {
            return 'scrollbar: ' . json_encode($this->scrollbar->scrollbar) . ",\n";
        }

        return '';
    }
}
