<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PDF</title>
  </head>
  <body>

    <table style="width: 100%;border: 1px solid #000;margin: 0 auto;padding: 20px;font-family: Arial, Helvetica, sans-serif;">

      <tr>
        <td>
          <table style="width: 100%;">
            <tr>
              <td style="width:15%;vertical-align: top;">@if($pdf_data['logo'] !='')<img style="width:130px;" src="{{ $pdf_data['logo'] }}">@endif</td>
              <td style="width:85%;">
                <h2 style="margin: 0px 0 3px 0;color: #2c308e;font-weight: 600;">{{ $pdf_data['header']['title'] }}</h2>
                <p style="margin: 0px 0 0px 0;font-weight: 600;font-size: 14px;">{{ $pdf_data['header']['address'] }}</p>
                <p style="margin: 0px 0 0px 0;font-weight: 600;font-size: 14px;">{{ $pdf_data['header']['contact'] }}</p>
                <p style="margin: 0px 0 0px 0;font-weight: 600;font-size: 14px;">{{ $pdf_data['header']['union'] }}</p>
                <p style="margin: 0px;font-weight: 600;font-size: 14px;">{{ $pdf_data['header']['gstin'] }}</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <tr>
        <td>
          <table style="width: 100%;border-bottom:2px solid #000;">
            <tr>
              <td style="font-weight: 700;font-size: 22px; text-align: right;">Order Invoice</td>
            </tr>
          </table>
        </td>
      </tr>

      <tr>
        <td>
          <table style="width: 100%;">
            <tr>
              <td style="vertical-align: top;width: 7%;"><p style="font-weight:400;font-size: 14px;margin: 0px;">To</p></td>
              <td style="width: 63%;">
                <p style="font-weight:400;font-size: 14px;margin: 0px;">{{$serviceOrder->client->client_name ?? ''}}</p>
                @php $address = explode("|", wordwrap($serviceOrder->client->client_address,35,"|")); @endphp
                @if(isset($address[0]))<p style="font-weight:400;font-size: 14px;margin: 0px;">{{$address[0]}}</p>@endif
                @if(isset($address[1]))<p style="font-weight:410;font-size: 14px;margin: 0px;">{{$address[1]}}</p>@endif
                @if(isset($address[2]))<p style="font-weight:400;font-size: 14px;margin: 0px;">{{$address[2]}}</p>@endif
                <p style="font-weight:400;font-size: 14px;margin: 0px;">Tel:  {{$serviceOrder->client->poc_contact ?? ''}}</p>
                <p style="font-weight:400;font-size: 14px;margin: 0px;">Att:   {{$serviceOrder->client->poc_name ?? ''}}</p>
              </td>
              <td style="width: 30%;">
                <p style="font-weight:400;font-size: 14px;margin: 0px;"><span style="width: 60px;display: inline-block;">No</span>: {{$serviceOrder->service_order_id ?? ''}}</p>
                <p style="font-weight:400;font-size: 14px;margin: 0px;"><span style="width: 60px;display: inline-block;">Date</span>: {{$serviceOrder->completed_date ?? ''}}</p>
                <p style="font-weight:400;font-size: 14px;margin: 0px;"><span style="width: 60px;display: inline-block;">Ref</span>: </p>
                <p style="font-weight:400;font-size: 14px;margin: 0px;"><span style="width: 60px;display: inline-block;">Staff</span>: </p>
                <p style="font-weight: 400; font-size: 14px; margin: 0px;">
                    <span style="width: 60px; display: inline-block;">Terms</span>:
                    {{config('constants.payment_terms')[$serviceOrder->client->payment_terms]}}
                </p>
                {{-- <p style="font-weight:400;font-size: 14px;margin: 0px;"><span style="width: 60px;display: inline-block;">Terms</span>: {{$serviceOrder->client->payment_terms ?? ''}}</p> --}}
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <tr>
        <td>
          <table>
            <tr><td style="height: 20px;"></td></tr>
          </table>
        </td>
      </tr>

      <tr>
        <td>
          <table style="width: 100%;border: 1px solid #000;border-collapse: collapse;">
            <tr>
              <th style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: center;">SNo</th>
              <th style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: center;">Item Code</th>
              <th style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: center;">Description</th>
              <th style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: center;">Quantity</th>
              <th style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: center;">Unit Price</th>
              <th style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: center;">Amount</th>
            </tr>

            <!-- Item list start -->
            @php $length_row = 30 - $serviceOrder->items->count(); @endphp
            @foreach($serviceOrder->items as $key => $item)
              <tr>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: center;">{{$key+1}}</td>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: center;">@if($item->type == 'inventory') {{$item->product_variant->product_sku_code}} @else {{$item->item_name ?? ''}} @endif</td>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: left;">{{ $item->quantity ?? ''}}</td>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: center;">{{ $item->total_run ?? 0}}FT</td>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;">{{ $item->price ?? ''}}</td>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;">{{ $item->amount ?? ''}}</td>
              </tr>
            @endforeach
              <tr>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
              </tr>
              <tr>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;">Subtotal</td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;">{{$serviceOrder->invoice->sub_total}}</td>
              </tr>
              <tr>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;">Discount</td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;">{{$serviceOrder->invoice->discount_amount}}</td>
              </tr>
            @if($length_row > 0)
              @for($i = 0; $i <= $length_row; $i++)
                <tr>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                </tr>
              @endfor
            @endif
           <!-- Item list end -->

            <tr>
              <td style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: left;"colspan="3"> {{ numberToWord(round($serviceOrder->invoice->amount,2)) }}</td>
              <td style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: right;">
                <p style="font-size: 14px;margin: 0px;">Amount:</p>
                <p style="font-size: 14px;margin: 0px;">{{ $serviceOrder->invoice->gst_percent.'%' }} GST:</p>
                <p style="font-size: 14px;margin: 0px;">Total SGD:</p>
              </td>
              <td style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: right;"colspan="2">
                <p style="font-size: 14px;margin: 0px;">{{ $serviceOrder->invoice->amount - $serviceOrder->invoice->gst_amount}}</p>
                <p style="font-size: 14px;margin: 0px;">{{ $serviceOrder->invoice->gst_amount ?? 0 }}</p>
                <p style="font-size: 14px;margin: 0px;">{{ $serviceOrder->invoice->amount }}</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>

    </table>

  </body>
</html>
