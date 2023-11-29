<?php
namespace App\Interfaces;

use Intervention\Image\Filters\FilterInterface;

/**
 * A simple filter to get the predominant color from an image using the
 * Intervention library (http://image.intervention.io).
 *
 * Usage with no options:
 * $image = Image::make($file);
 * $primaryColor = $image->filter(new PrimaryColor());
 *
 * Usage with options:
 * $image = Image::make($file);
 * $filter = new PrimaryColor();
 * $primaryColor = $image->filter($filter->format('hex')
 *                         ->ignores(['#ffffff']));
 */

class PrimaryColor implements FilterInterface
{
    /**
     * The color format to return
     *
     * @var string
     */
    protected $format = 'hex';

    /**
     * Colors to ignore
     * 
     * @var array
     */
    protected $ignores = [
        '#ffffff',
        '#000000'
    ];

    /**
     * The color format to return
     *
     * @var int
     */
    protected $excludePercentage = 30;

    /**
     * Applies filter effects to given image
     *
     * @param  \Intervention\Image\Image $image
     * @return array
     */
    public function applyFilter(\Intervention\Image\Image $image)
    {
        $imageWidth = $image->width();
        $imageHeight = $image->height();

        // Calculate the size of the exclusion zone
        $excludeZoneWidth = ($this->excludePercentage / 100) * $imageWidth;
        $excludeZoneHeight = ($this->excludePercentage / 100) * $imageHeight;

        // Calculate the starting point for the loop (excluding the outer 20%)
        $startX = $excludeZoneWidth;
        $startY = $excludeZoneHeight;

        // Calculate the ending point for the loop (excluding the outer 20%)
        $endX = $imageWidth - $excludeZoneWidth;
        $endY = $imageHeight - $excludeZoneHeight;

        // Create an array to store unique colors
        $uniqueColors = [];

        // Loop through the pixels within the inner 60% of the image
        for ($x = $startX; $x < $endX; $x += 100) {
            for ($y = $startY; $y < $endY; $y += 100) {
                $pixelColor = $image->pickColor(intval($x), intval($y), 'hex');
                if (isset($occurrences[$pixelColor])) {
                    $occurrences[$pixelColor]++;
                } else {
                    $occurrences[$pixelColor] = 1;
                }
            }
        }

        arsort($occurrences);
        
        $this->removeIgnoredColors($occurrences);

        return key($occurrences);
    }

    /**
     * Remove all the ignored colors from the results
     * 
     * @param  array &$occurrences
     * @return void
     */
    protected function removeIgnoredColors(&$occurrences)
    {
        foreach ($this->ignores as $color) {
            unset($occurrences[$color]);
        }
    }

    /**
     * Set the colors to ignore
     * 
     * @param  array  $colors
     * @return $this
     */
    public function ignores(array $colors)
    {
        $this->ignores = $colors;

        return $this;
    }

    /**
     * Set the color format
     * 
     * @param  string $format
     * @return $this
     */
    public function format($format)
    {
        $this->format = $format;

        return $this;
    }
}