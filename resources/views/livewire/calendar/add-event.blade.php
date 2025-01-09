<div class="mt-4">
    <div>
        <button onclick="openModal('addEventModal')" class="btn btn-outline-primary btn-lg col-6 w-25"><i
                data-lucide="circle-fading-plus" class="icon icon-large me-2"></i> Add New Event</button>
    </div>

    <div class="mt-3">
        <!-- BACK-END REPLACE: Redirection to the Events table,
        where they can edit the holiday, types, etc.
        
        Opted for redirection to a table instead of directly editing it in the calendar for less strenuous code  work.-->

        <a class="text-link-blue hover-opacity" href="{{ route($routePrefix . '.calendar.list') }}">See and manage all events.
        </a>
    </div>
</div>