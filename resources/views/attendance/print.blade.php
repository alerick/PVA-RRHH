<?php
use \Carbon\Carbon;
use \Milon\Barcode\DNS2D;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>PLATAFORMA VIRTUAL ADMINISTRATIVA - MUSERPOL </title>
  <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all"/>
  <style>
    .scissors-rule {
      display: block;
      text-align: center;
      overflow: hidden;
      white-space: nowrap;
      margin-top: 5px;
      margin-bottom: 12px;
    }
    .scissors-rule > span {
      position: relative;
      display: inline-block;
    }
    .scissors-rule > span:before,
    .scissors-rule > span:after {
      content: "";
      position: absolute;
      top: 50%;
      width: 9999px;
      height: 1px;
      background: white;
      border-top: 1px dashed black;
    }
    .scissors-rule > span:before {
      right: 100%;
      margin-right: 5px;
    }
    .scissors-rule > span:after {
      left: 100%;
      margin-left: 5px;
    }
    .border-left-white {
      border-left: 1px solid white;
    }
    .p-l-5 {
      padding-left: 5px;
    }
    .monospace {
      font-family: "Roboto Mono", monospace;
    }
  </style>
</head>
<body style="border: 0; border-radius: 0;">
  @php
    $p = 0;
  @endphp

  @foreach($employees as $check)
    @php
      $from = Carbon::parse($check['from'])->startOfWeek(Carbon::MONDAY);
      $to = Carbon::parse($check['to'])->endOfWeek(Carbon::SUNDAY);
    @endphp

    @if($p % 2 == 0)
      <div>
    @else
      <div class="scissors-rule">
        <span>&#9986;</span>
      </div>
      <div style="page-break-after: always;">
    @endif

      <table class="w-100 uppercase">
        <tr>
          <th class="w-15 text-left no-padding no-margins align-middle">
            <div class="text-left">
              <img src="{{ public_path("/img/logo.png") }}" class="w-40">
            </div>
          </th>
          <th class="w-70 align-top">
            <div class="font-hairline leading-tight text-xxs" >
              <div>MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"</div>
              <div>DIRECCIÓN DE ASUNTOS ADMINISTRATIVOS</div>
              <div>UNIDAD DE RECURSOS HUMANOS</div>
            </div>
          </th>
          <th class="w-15 no-padding no-margins align-top">
            <table class="table-code no-padding no-margins text-xxs uppercase">
              <tbody>
                <tr>
                    <td class="text-center bg-grey-darker text-white">Desde </td>
                    <td> {{ Carbon::parse($check['from'])->ISOFormat('L') }} </td>
                </tr>
                <tr>
                  <td class="text-center bg-grey-darker text-white">Hasta</td>
                  <td> {{ Carbon::parse($check['to'])->ISOFormat('L') }} </td>
                </tr>
              </tbody>
            </table>
          </th>
        </tr>
      </table>
      <hr class="m-b-10" style="margin-top: 0; padding-top: 0;">
      <div class="block">
        <div class="font-semibold leading-tight text-center m-b-10 text-xs">REGISTRO DE ASISTENCIA</div>

        <table class="table-info w-100 m-b-10 uppercase text-xs">
          <tbody>
            <tr>
              <td style="border-top: 1px solid #5d6975" class="w-10 text-center bg-grey-darker text-white font-light">Nombre</td>
              <td style="border-top: 1px solid #5d6975" class="w-23 font-thin p-l-5">{{ $check['employee']->full_name }}</td>
              <td style="border-top: 1px solid #5d6975" class="w-10 text-center bg-grey-darker text-white font-light">Cargo</td>
              <td style="border-top: 1px solid #5d6975" class="w-55 font-thin p-l-5">{{ $check['employee']->position_name }}</td>
            </tr>
          </tbody>
        </table>

        <table class="table-info w-100 m-b-10 uppercase text-xxs monospace" style="padding-top: 4px;">
          <thead>
            <tr>
              <th class="text-center bg-grey-darker text-white">LUN</th>
              <th class="text-center bg-grey-darker text-white border-left-white">MAR</th>
              <th class="text-center bg-grey-darker text-white border-left-white">MIE</th>
              <th class="text-center bg-grey-darker text-white border-left-white">JUE</th>
              <th class="text-center bg-grey-darker text-white border-left-white">VIE</th>
              <th class="text-center bg-grey-darker text-white border-left-white">SAB</th>
              <th class="text-center bg-grey-darker text-white border-left-white">DOM</th>
            </tr>
          </thead>
          <tbody class="table-striped text-xxs">
            @while($from->lessThan($to))
            @if($from->dayOfWeek == Carbon::MONDAY)
            <tr class="font-thin">
            @endif
              <td class="w-14 align-text-top">
                <p class="text-left" style="margin-top: 0; margin-bottom: 0; padding-left: 4px; padding-top: 2px; padding-bottom: 0;">
                  {{ $from->day }}
                  @if($from->day == 1 || $from == Carbon::parse($check['from'])->startOfWeek(Carbon::MONDAY))
                    <span class="px-10">{{ $from->ISOFormat('MMMM') }}</span>
                  @endif
                </p>
                @php ($current_date = $from->toDateString())
                @if(array_key_exists($current_date, $check['checks']))
                  @foreach ($check['checks'][$current_date] as $period)
                    <p class="text-center" style="margin-top: 0; margin-bottom: 0; padding-top: 2px; padding-bottom: 0;">
                    @foreach ($period as $i => $c)
                      @if ($i>0)
                        <span> - </span>
                      @endif
                      <span class="{{ $c->delay->minutes != 0 ? 'bg-grey-darker text-white px-5' : '' }}"> {{ $c->time }} </span>
                    @endforeach
                    </p>
                  @endforeach
                @endif
              </td>
            @if($from->dayOfWeek == Carbon::SUNDAY)
            </tr>
            @endif
            @php ($from->addDay())
            @endwhile
          </tbody>
        </table>
      </div>
      <table>
        <tr>
          <td class="text-xxxxs" align="left">
            @if (env("APP_ENV") == "production")
              PLATAFORMA VIRTUAL ADMINISTRATIVA
            @else
              VERSIÓN DE PRUEBAS
            @endif
          </td>
          <td class="child" align="right">
            <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG(bcrypt($check['from'] . ' ' . gethostname() . ' ' . env('APP_URL')), 'PDF417') }}" alt="BARCODE!!!" style="height: 22px; width: 125px;" />
          </td>
        </tr>
      </table>
    </div>
    <?php
      $p = $p + 1;
    ?>
  @endforeach
</body>
</html>
