@php
    use Barryvdh\DomPDF\Facade\Pdf;

    $coe = Pdf::loadView('coe');
    return $coe->download('coe');
@endphp


