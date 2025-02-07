{{ app('debugbar')->disable() }}
@use('Carbon\Carbon')
@use('Illuminate\Support\Facades\Storage')
@use('App\Enums\FilePath')
@use('App\Http\Helpers\File')



@props([
    'certName' => 'Certificate of Employment',
    'certDesc' => 'This is to certify that',
    'name',
    'empStart',
    'empEnd',
    'jobTitle',
    'jobDepartment',
    'issuedDate',
    'hrManager',
    'companyAddr',
])


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style nonce="{{ $nonce }}">
        {!! Vite::content('node_modules/bootstrap/dist/css/bootstrap.css') !!}
    </style>

    <style>
        * {
            inset: 0;
        }

        body {
            background-color: white;
            width: 100%;
            height: 250px;
            max-height: 100vh;
        }

        div:has(> main) {
            max-height: 50vh;
            display: flex;
        }

        .powerlane-logo {
            top: -8%;
            left: -0.5%;
        }

        .greens-vector {
            height: 150%;
            width: auto;
        }

        .greens-vector.start-0 {
            z-index: -1;
            top: 30%;
            transform: rotate(180deg);
        }


        main {
            height: 100cqh;
            width: 100%;
            max-height: 48vh;
            background-color: white;
        }

        hr {
            color: #cccc33 border: 1px solid #cccc33;
            margin-top: 0.05em;
        }

        .cert-title {
            max-width: 75ch;
        }

        .cert-title h1 {
            font-size: 32px;
        }

        .person-name {
            font-size: 32px;
        }

        div:has(> .person-name) {}

        .content{
            /* padding: 0 50px */
        }
    </style>
</head>

{{-- @php
    $certParts = explode(' ', trim($certName), 2);
    // sheesh saves headache
    $issueDay = Carbon::parse($issuedDate)->isoFormat('Do');
    $issueMonth = Carbon::parse($issuedDate)->format('F');
    $issueYear = Carbon::parse($issuedDate)->isoFormat('YYYY');

    $vectorFile = resource_path('images/illus/co-employment/greens-vector.png');
    $vectorData = base64_encode(file_get_contents($vectorFile));

    $logoFile = resource_path('images/illus/co-employment/powerlane-logo.png');
    $logoData = base64_encode(file_get_contents($logoFile));
@endphp --}}

@php
    $certName = 'Certificate of Employment';
    $certParts = explode(' ', trim($certName), 2);
    $certDesc = 'This is to certify that';
    $name = 'John Doe';
    $empStart = '2021-01-01';
    $empEnd = '2021-12-31';
    $jobTitle = 'Software Developer';
    $jobDepartment = 'IT';
    $issuedDate = '2021-12-31';
    // sheesh saves headache
    $issueDay = Carbon::parse($issuedDate)->isoFormat('Do');
    $issueMonth = Carbon::parse($issuedDate)->format('F');
    $issueYear = Carbon::parse($issuedDate)->isoFormat('YYYY');
    $hrManager = 'Jane Doe';
    $companyAddr = '123 Main St, City, State, Zip';

    $vectorFile = resource_path('images/illus/co-employment/greens-vector.png');
    $vectorData = base64_encode(file_get_contents($vectorFile));

    $logoFile = resource_path('images/illus/co-employment/powerlane-logo.png');
    $logoData = base64_encode(file_get_contents($logoFile));
@endphp

<body class="p-5 position-relative">
    <div class="position-absolute end-0 greens-vector">
        <img src="data:image/png;base64,{{ $vectorData }}" class="float-end" height="100%" alt="">
    </div>
    <div class="p-5">
        <div class="border border-5  p-3 position-relative">
            <img src="data:image/png;base64,{{ $logoData }}" class="position-absolute powerlane-logo" alt=""
                height="150px" width="150px">
            <main class="d-non p-2">
                <div class="text-center content">

                    <hgroup class="cert-title mx-auto">
                        <h1 class="text-uppercase fw-bold">{{ $certParts[0] }} <br> <span
                                class="fw-medium">{{ $certParts[1] }}</span></h1>
                        <p class="fs-2">{{ $certDesc }}</p>
                    </hgroup>

                    <hgroup class=" mx-auto">
                        <div class="person-name text-uppercase"> {{ $name }}</div>
                        <hr class="col-4 mx-auto">
                        <p>was employed at Powerlane Resources, Inc. from
                            <span>{{ Carbon::parse($empStart)->format('F j, Y') }}</span>
                            to
                            <span>{{ Carbon::parse($empEnd)->format('F j, Y') }}</span>
                            as a
                            <span>{{ $jobTitle }}</span>
                            on
                            <span>{{ $jobDepartment }}</span>
                        </p>
                    </hgroup>
                    <div class="mx-auto">
                        <p>This certificate is issued upon the employee's request for whatever purpose it may serve.</p>
                        <p>Issued this <span>{{ $issueDay }}</span> day of <span>{{ $issueMonth }}</span>
                            <span>{{ $issueYear }}</span> at <span>{{ $companyAddr }}</span>.
                        </p>
                    </div>

                    <hgroup class="cert-title mx-auto">
                        <p class="fs-2 mb-0">{{ $hrManager }}</p>
                        <h6 class="text-uppercase fw-bold">HR Manager</span></h6>
                    </hgroup>

                </div>
            </main>
        </div>
    </div>

    <div class="position-absolute greens-vector start-0">
        <img src="data:image/png;base64,{{ $vectorData }}" class="float-end" height="100%" alt="">
    </div>
</body>

</html>
