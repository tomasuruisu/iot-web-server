<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>IOT-app</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        html {
            line-height: 1.15;
            -webkit-text-size-adjust: 100%
        }

        body {
            margin: 0;
            font-family: 'Nunito', sans-serif;
        }

        .container {
            display: flex;
            justify-content: space-evenly
        }

        .section {
            flex-direction: column;
            flex: 1;
            padding: 10px;
        }

        .weather {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

    </style>
</head>

<body class="antialiased">
    <div class="container">
        <div class="section">
            <h1>latest light values</h1>
            <table>
                <tr style="text-align: left">
                    <th>id</th>
                    <th>value</th>
                    <th>time</th>
                </tr>
                @foreach ($light_values as $light_value)
                <tr>
                    <td>{{ $light_value->id }}</td>
                    <td>{{ $light_value->value }}</td>
                    <td>{{ $light_value->time }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="section">
            <h1> {{ $weather->name }} </h1>
            <div class="weather">
                <img src="{{'http://openweathermap.org/img/wn/'. $weather->weather[0]->icon .'@2x.png'}}" alt="{{ $weather->weather[0]->description}}">
                <p> {{ $weather->weather[0]->description }} </p>
            </div>
            <p>Sunrise at: {{ $weather->sys->sunrise }}</p>
            <p>Sunset at: {{ $weather->sys->sunset }}</p>
        </div>
    </div>
</body>

</html>
