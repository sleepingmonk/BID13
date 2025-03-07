<?php
/**
 * @file
 * Generate a scatter plot from data in a CSV file.
 */

if ($argv[1] == "--help" || $argv[1] == "-h") {
  echo <<<END

  This script generates a scatter plot from data.csv

  USAGE:  php src/scatterPlot/scatterPlot.php



  END;

  return;
}

$csvFile = __DIR__ . '/data.csv';
$data = array_map('str_getcsv', file($csvFile));
array_shift($data);

// Extract x and y values to help with plot dimensions.
$xValues = array_column($data, 0);
$yValues = array_column($data, 1);
$padding = 50;
$size = 5;

// Set image dimensions based on the max/min values of x and y.
$width = (min($xValues) < 0
  ? max($xValues) - min($xValues)
  : max($xValues))
  + 2 * $padding;

$height = (min($yValues) < 0
  ? max($yValues) - min($yValues)
  : max($yValues))
  + 2 * $padding;

// Set axis offsets to accommodate negative plot coordinates.
$xOffset = min($xValues) < 0 ? abs(min($xValues)) : 0;
$yOffset = min($yValues) < 0 ? abs(min($yValues)) : 0;

// Create the image.
$image = imagecreatetruecolor($width, $height);

// Colors
$black = imagecolorallocate($image, 0, 0, 0);
$yellow = imagecolorallocate($image, 255, 255, 53);

// Fill the background with black.
imagefill($image, 0, 0, $black);

// Plot the points.
foreach ($data as $row) {
  $x = $padding + $row[0] + $xOffset;
  $y = $padding + $row[1] + $yOffset;
  imagefilledellipse($image, $x, $y, $size, $size, $yellow);
}

// Output the image to a file.
imagepng($image, __DIR__ . '/../../scatterPlot.png');

// Release from memory.
imagedestroy($image);
