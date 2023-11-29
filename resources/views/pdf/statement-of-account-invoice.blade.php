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
                <p style="margin: 0px; font-weight: 600;font-size: 14px;">{{ $pdf_data['header']['gstin'] }}</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <tr>
        <td>
          <table style="width: 100%;border-bottom:2px solid #000;">
            <tr>
              <td style="font-weight: 700;font-size: 22px; text-align: right;">Statement of Account</td>
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
                <p style="font-weight:400;font-size: 14px;margin: 0px;">{{$accountStatement->client->client_name ?? ''}}</p>
                @php $address = explode("|", wordwrap($accountStatement->client->client_address,35,"|")); @endphp
                @if(isset($address[0]))<p style="font-weight:400;font-size: 14px;margin: 0px;">{{$address[0]}}</p>@endif
                @if(isset($address[1]))<p style="font-weight:410;font-size: 14px;margin: 0px;">{{$address[1]}}</p>@endif
                @if(isset($address[2]))<p style="font-weight:400;font-size: 14px;margin: 0px;">{{$address[2]}}</p>@endif
                <p style="font-weight:400;font-size: 14px;margin: 0px;">Tel:  {{$accountStatement->client->poc_contact ?? ''}}</p>
                <p style="font-weight:400;font-size: 14px;margin: 0px;">Att: {{$accountStatement->client->poc_name ?? ''}}</p>
              </td>
              <td style="width: 30%;">
                <p style="font-weight:400;font-size: 14px;margin: 0px;"><span style="width: 40px;display: inline-block;"></span></p>
                <p style="font-weight:400;font-size: 14px;margin: 0px;"><span style="width: 40px;display: inline-block;">Date</span>: {{ optional($accountStatement->created_at)->format('d/m/Y') }}</p>
                <p style="font-weight:400;font-size: 14px;margin: 0px;"><span style="width: 40px;display: inline-block;"></span></p>
                <p style="font-weight:400;font-size: 14px;margin: 0px;"><span style="width: 40px;display: inline-block;"></span></p>
                <p style="font-weight:400;font-size: 14px;margin: 0px;"><span style="width: 40px;display: inline-block;">SGD</span></p>
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
              <th style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: center;">Date</th>
              <th style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: center;">Document</th>
              <th style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: center;">Debit</th>
              <th style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: center;">Credit</th>
              <th style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: center;">Balance</th>
            </tr>

            <!-- Item list start -->
            @php $length_row = 30 - $accountStatement->jobs->count(); $balance=0; @endphp
            @foreach($accountStatement->jobs as $key => $serviceOrders)
            @php $balance += $serviceOrders->invoice->amount; @endphp
              <tr>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: center;">{{optional($serviceOrders->created_at)->format('d/m/Y') }}</td>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: center;">{{ $serviceOrders->invoice->invoice_number ?? ''}} ({{$serviceOrders->service_order_id ?? ''}})</td>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: center;">{{ round($serviceOrders->invoice->amount,2) ?? 0}}</td>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: center;">{{ '—' }}</td>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;">{{ round($balance,2) }}</td>
              </tr>
            @endforeach
            
            @php $length_row = $length_row - $accountStatement->credit_notes->count(); @endphp
            @foreach($accountStatement->credit_notes as $key => $note)
            @php $balance = round($balance,2) - round($note->pivot->amount,2); @endphp
              <tr>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: center;">{{optional($note->created_at)->format('d/m/Y') }}</td>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: center;">{{ $note->note_id ?? ''}}</td>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: center;">{{ '—' }}</td>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: center;">{{ round($note->pivot->amount,2) ?? 0}}</td>
                <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;">{{ round($balance,2) }}</td>
              </tr>
            @endforeach
              <tr>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
                  <td style="font-size: 14px;border-left:1px solid #000;border-right:1px solid #000;border-top:0px;border-bottom:0px;padding: 5px;text-align: right;"></td>
              </tr>
            @if($length_row > 0)
              @for($i = 0; $i <= $length_row; $i++)
                <tr>
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
              <td style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: left;"colspan="2">Balance C/F :</td>
              <td style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: right;">
                <p style="font-size: 14px;margin: 0px;"></p>
              </td>
              <td style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: right;">
                <p style="font-size: 14px;margin: 0px;"></p>
              </td>
              <td style="font-size: 14px;border:1px solid #000;padding: 5px;text-align: right;">
                <p style="font-size: 14px;margin: 0px;">{{ round($balance,2) }} {{--$accountStatement->payable_amount  --}}</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>

    </table>

  </body>
</html>

