{{-- Success Callout: Displays a green check badge icon with a success message --}}
<x-info_panels.callout 
    type="success" 
    description="Your action was successful!"
/>

{{-- Warning Callout: Displays a yellow warning icon with a caution message --}}
<x-info_panels.callout 
    type="warning" 
    description="Please be cautious with the changes."
/>

{{-- Error Callout: Displays a red error icon with an error notification --}}
<x-info_panels.callout 
    type="error" 
    description="An error occurred. Please try again later."
/>

{{-- Info Callout: Displays a blue info icon with a general informational message --}}
<x-info_panels.callout 
    type="info" 
    description="This is a general informational message."
/>

{{-- Default Callout (No Type Specified): Defaults to info type, with blue info icon --}}
<x-info_panels.callout 
    description="This is a default informational message without specifying a type."
/>
