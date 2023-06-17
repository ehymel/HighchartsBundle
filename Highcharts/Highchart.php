<?php

namespace Ob\HighchartsBundle\Highcharts;

/**
 * This class is part of the Ob/HighchartsBundle
 * See Highcharts documentation at http://www.highcharts.com/ref/
 *
 * @method Highchart colors(array $colors)
 * @method Highchart series(array $series)
 */
class Highchart extends AbstractChart implements ChartInterface
{
    public $colorAxis;

    public $noData;

    public function __construct()
    {
        parent::__construct();
        $this->initChartOption('colorAxis');
        $this->initChartOption('noData');
    }

    public function render(string $engine = 'jquery'): string
    {
        $chartJS = "";
        $chartJS .= $this->renderEngine($engine);
        $chartJS .= $this->renderOptions();
        $chartJS .= "\n    var " . (isset($this->chart->renderTo) ? $this->chart->renderTo : 'chart') . " = new Highcharts.Chart({\n";

        // Chart
        $chartJS .= $this->renderWithJavascriptCallback($this->chart->chart, "chart");

        // Colors
        $chartJS .= $this->renderColors();

        // Color Axis
        $chartJS .= $this->renderColorAxis();

        // Credits
        $chartJS .= $this->renderCredits();

        // Exporting
        $chartJS .= $this->renderWithJavascriptCallback($this->exporting->exporting, "exporting");

        // Labels

        // Legend
        $chartJS .= $this->renderWithJavascriptCallback($this->legend->legend, "legend");

        // Loading
        // Navigation

        // noData
        $chartJS .= $this->renderWithJavascriptCallback($this->noData->noData, "noData");

        // Pane
        $chartJS .= $this->renderPane();

        // PlotOptions
        $chartJS .= $this->renderWithJavascriptCallback($this->plotOptions->plotOptions, "plotOptions");

        // Series
        $chartJS .= $this->renderWithJavascriptCallback($this->series, "series");

        // Scrollbar
        $chartJS .= $this->renderScrollbar();

        // Drilldown
        $chartJS .= $this->renderDrilldown();

        // Subtitle
        $chartJS .= $this->renderSubtitle();

        // Symbols

        // Title
        $chartJS .= $this->renderTitle();

        // Tooltip
        $chartJS .= $this->renderWithJavascriptCallback($this->tooltip->tooltip, "tooltip");

        // xAxis
        $chartJS .= $this->renderXAxis();

        // yAxis
        $chartJS .= $this->renderYAxis();

        // trim last trailing comma and close parenthesis
        $chartJS = rtrim($chartJS, ",\n") . "\n    });\n";

        if ($engine !== false) {
            $chartJS .= "});\n";
        }

        return trim($chartJS);
    }

    private function renderColorAxis(): string
    {
        if (gettype($this->colorAxis) === 'array') {
            return $this->renderWithJavascriptCallback($this->colorAxis, "colorAxis");
        } elseif (gettype($this->colorAxis) === 'object') {
            return $this->renderWithJavascriptCallback($this->colorAxis->colorAxis, "colorAxis");
        }

        return "";
    }

    private function renderPane(): string
    {
        if (get_object_vars($this->pane->pane)) {
            return "pane: " . json_encode($this->pane->pane) . ",\n";
        }

        return "";
    }

    private function renderDrilldown(): string
    {
        if (get_object_vars($this->drilldown->drilldown)) {
            return "drilldown: " . json_encode($this->drilldown->drilldown) . ",\n";
        }

        return "";
    }
}
