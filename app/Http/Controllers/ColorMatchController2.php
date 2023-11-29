<?php

namespace App\Http\Controllers;

use Spatie\Color\Color;
use Spatie\Color\Hex;
use Spatie\Color\Rgb;
use Spatie\Color\Distance;
use App\Repositories\ProductRepository;
use App\Interfaces\PrimaryColor;
use Intervention\Image\Facades\Image;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Validator, Datatables;

class ColorMatchController2 extends Controller
{
    protected $productRepo;

    public function __construct(ProductRepository $productRepo){
        $this->productRepo = $productRepo;
    }

     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = $this->pageSetting();

        return view('pages.floor-operations.color-module.wrapper',compact('page'));
    }

    public function getColor(Request $request){
        $productSku=[]; $productHtml='';
        if($request->has('image')){
            $colorCodes = $this->findColor();

            $threshold = 15;
            $products = $this->productRepo->get()->get();
            foreach ($products as $product) {

                if($product->is_color_matching && $product->color_code){
                    $attachment = $product->attachments->first();
                    foreach($colorCodes as $colorCode){
                        $colorDifference = Distance::CIE76($colorCode, $product->color_code);
                        if ($colorDifference <= $threshold) {
                            $productHtml .= '<div class="form-group col-sm-1">
                                                    <div class="product-card text-center">'
                                                    .( $product->attachments->count() ?
                                                    '<img src="'.url('storage/files/attachments/'.$attachment->attachment_directory.'/'.$attachment->attachment_filename).'" alt="" width="50" height="50">'
                                                    :'' ).'<h5>'.$product->sku_code.'</h5>
                                                    </div>
                                                </div>';
                                            continue 2;
                        }
                    }
                }
            }
        }

        $html = '<div class="form-group col-md-2 d-flex align-items-center">
                    <label for="client" class="font-weight-bold">Recommended Edge Colour:</label>
                </div>';
        if($productHtml != ''){
            $html .= $productHtml;
        }else{
            $html .= '<div class="form-group col-md-2 d-flex align-items-center">
                <p>0 Result found</p>
            </div>';
        }

        $response['dom_html'][] = array(
            'selector' => '#recommended-product-list',
            'action' => 'replace',
            'value' => $html);

        return response()->json($response,200);

    }

    /**
     * find colors in image
     */
    public function findColor(){
        $image = Image::make(request()->file('image'));

        // Get image dimensions
        $imageWidth = $image->width();
        $imageHeight = $image->height();

        // Calculate center coordinates
        $centerX = $imageWidth / 2;
        $centerY = $imageHeight / 2;

        $colorCode = $image->pickColor(intval($centerX), intval($centerY), 'hex');

        // Define the percentage to exclude from all sides
        $excludePercentage = 30;

        // Calculate the size of the exclusion zone
        $excludeZoneWidth = ($excludePercentage / 100) * $imageWidth;
        $excludeZoneHeight = ($excludePercentage / 100) * $imageHeight;

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
                $uniqueColors[] = $pixelColor;
            }
        }
        $colorGroups = [];
        for ($i = 0; $i < count($uniqueColors); $i++) {
            for ($j = $i + 1; $j < count($uniqueColors); $j++) {
                $hexColor1 = $uniqueColors[$i];
                $hexColor2 = $uniqueColors[$j];
                $difference = Distance::CIE76($hexColor1, $hexColor2);

                $colorDifferences[] = ['difference' => $difference, 'color1' => $hexColor1, 'color2' => $hexColor2];
            }
        }

        // Sort the color differences in ascending order
        usort($colorDifferences, function ($a, $b) {
            return $a['difference'] - $b['difference'];
        });

        $threshold = 0.2 * max(array_column($colorDifferences, 'difference'));
        $numberCounts = [];

        foreach ($colorDifferences as $item) {
            if ($item['difference'] <= $threshold) {
                $similarColor1[] = $item['color1'];
                $similarColor2[] = $item['color2'];
                if (!isset($numberCounts[intval($item['difference'])])) {
                    $numberCounts[intval($item['difference'])] = 1;
                } else {
                    $numberCounts[intval($item['difference'])]++;
                }
                // echo '<div style="color:#fff;float:left;width:50px;height:50px;background:'.$item['color2'].'">'.intval($item['difference']).'</div>';
            }
        }

        arsort($numberCounts);


        $excludeCount = ceil(count($numberCounts) * 0.3);

        // Use array_slice to exclude elements
        $keysToExclude = array_slice(array_keys($numberCounts), -$excludeCount, $excludeCount, true);
        $filteredDistances = array_diff_key($numberCounts, array_flip($keysToExclude));

        // echo '<div style="width:100%;"><br>'; print_r($filteredDistances);echo '<br></div>';dd();
        $find=[];
        foreach ($colorDifferences as $item) {
            if(count($filteredDistances) > 2){
                if(array_key_exists(intval($item['difference']),$filteredDistances) && $item['difference'] <= 40) {
                    $find[]=$item['color2'];
                }
            }else{
                $find[]=$item['color2'];
            }
        }

        $findColors = array_unique($find);
        // foreach($findColors as $c){
        //     echo '<div style="color:#fff;float:left;width:50px;height:50px;background:'.$c.'"></div>';
        // }die;
        return $findColors;
    }

    /**
     * Page content.
     */
    public function pageSetting($type = null, $data=null)
    {
        $page = [
            'pageTitle' =>'Colour Match Module',
            'previousUrl' => url('floor-operations')
        ];

        return $page;
    }
}
