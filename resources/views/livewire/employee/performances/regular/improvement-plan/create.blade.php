<div class="d-contents">
    <x-employee.performance.pip.content-block :pipData="$pipData" :performance="$performance" />

    <div class="col-2 mt-4 px-2">

        <form action="{{ route($routePrefix . '.performances.plan.improvement.regular.' . ($routeMethod ?? 'store')) }}"
            method="post" class="d-contents">

            @if ($method)
                @method($method)
                <input type="hidden" name="pipId" value="{{ $performance->pip->pip_id }}">
            @endif

            @csrf
            <input type="hidden" name="performanceId" value="{{ $performance->regular_performance_id }}">
            <input type="hidden" name="pipDetails" value="{{ $pipData }}">
            <button type="submit" class="btn btn-primary btn-lg w-100 ">Save</button>
        </form>
    </div>
</div>
