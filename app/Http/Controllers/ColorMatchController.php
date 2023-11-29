<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;
use App\Repositories\ProductRepository;

class ColorMatchController extends Controller
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

    public function getColor(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        $path = $request->file('image')->store('uploads');

        $target_file = storage_path("app/{$path}");

        $palette = Palette::fromFilename($target_file);
        $extractor = new ColorExtractor($palette);
        $colors = $extractor->extract(5); // Extract 5 dominant colors

        $matching_products = []; $productSku=[]; $productHtml='';
        $products = $this->productRepo->get()->get();
        foreach ($products as $product) {
            if($product->is_color_matching && $product->color_code){
                $attachment = $product->attachments->first();
                    $productColor = $this->hex2rgb($product->color_code);
                    foreach ($colors as $color) {
                        $extractedColor = $this->hex2rgb(Color::fromIntToHex($color));
                        $distance = $this->getColorDistance($productColor, $extractedColor);
                        if ($distance < 150) {
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

    function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $result = [$r, $g, $b];
        return $result;
    }

    function getColorDistance($color1, $color2) {
        list($r1, $g1, $b1) = $color1;
        list($r2, $g2, $b2) = $color2;

        $delta_r = $r1 - $r2;
        $delta_g = $g1 - $g2;
        $delta_b = $b1 - $b2;

        return sqrt($delta_r * $delta_r + $delta_g * $delta_g + $delta_b * $delta_b);
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
