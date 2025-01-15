@use ('Illuminate\View\ComponentAttributeBag')

<div>
    <header>
        <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'pb-0 text-center'])" :overrideContainerClass="true" class="text-primary fs-1 fw-bold">
            <x-slot:heading>
                {{__('About Powerlane Resources, Inc.')}}
            </x-slot:heading>
        </x-headings.main-heading>
    </header>

    <body>
        <h4 class="fw-bold mt-0" class="mt-0">1.1 Company Profile</h4>

        <h4 class="fw-bold">1.1.1 History</h4>
        <p>
            POWERLANE RESOURCES, INC. (PRI) began its journey in March 1992, serving its first client, FEDCO PAPER.
            The company quickly gained momentum, adding THE REGION BANK (now known as PLANTERS BANK) to its roster
            shortly after.
        </p>
        <p>
            By early 1995, PRI's success necessitated a move to a larger office space. This expansion allowed the
            company
            to efficiently manage a growing workforce exceeding 1,000 employees and cater to the needs of a wider client
            base.
            Additionally, it established the first full-service branch office in Imus, Cavite. This branch later
            relocated to a more
            spacious location within Hausland Subdivision for improved service.
        </p>
        <p>
            PRI continued its strategic growth by opening additional branches in Gen. Trias, Cavite (catering to Gateway
            Business Park
            and First Cavite Industrial Estate) and Rosario, Cavite (August 2004).
        </p>
        <p>
            A significant milestone occurred in February 2005 when PRI earned accreditation from the Subic Bay
            Metropolitan Authority (SBMA).
            This achievement allowed it to establish a full-service branch within SBMA and provide manpower services to
            a new client,
            NIDEC Subic Phils., Inc.
        </p>
        <p>
            The company's commitment to expansion continued throughout the following decade. In 2010, a new branch
            opened in Ermita, Manila,
            to cater to the MAX's group of companies. PRI further solidified its national presence by launching branches
            in Makati (2011),
            Clark Freeport Zone, Pampanga, and Batangas.
        </p>

        <h4 class="fw-bold">1.1.2 Our Vision</h4>
        <p>
            POWERLANE RESOURCES, INC. (PRI). aims to be a leader and innovator in the field of manpower sourcing and
            human resources
            management, a significant contributor to the development of the local economies in which we operate, and a
            paragon of
            corporate social responsibility.
        </p>

        <h4 class="fw-bold">1.1.3 Our Mission</h4>
        <p>POWERLANE RESOURCES, INC. (PRI), seeks to:</p>
        <ul>
            <li>Provide a highly skilled and motivated workforce to meet the fast-paced and evolving organizational
                needs of its sophisticated clientele;</li>
            <li>Enable all employees to maintain a decent standard of living through fair wages and benefits;</li>
            <li>Maintain an active role in the development of the communities in which it operates through working
                partnerships with local governments and NGO’s.</li>
        </ul>

        <h4 class="fw-bold">1.1.4 Our Core Values</h4>
        <ul>
            <li><strong>P - Professionalism:</strong> We deliver exceptional service through a team of highly trained
                and dedicated professionals, fostering a positive and productive work environment.</li>
            <li><strong>R - Respect:</strong> We value the diverse skills and experiences of our workforce. We treat all
                workers with dignity and respect, fostering a safe and inclusive work environment.</li>
            <li><strong>I - Integrity:</strong> Powerlane Resources Inc. is committed to building trust through
                transparency and fairness in all interactions, ensuring a reliable and ethical partnership for both
                clients and workers.</li>
            <li><strong>Excellence:</strong> At Powerlane, we are passionate about continuous improvement. We actively
                seek feedback and implement innovative solutions to consistently exceed your expectations.</li>
        </ul>
    </body>
</div>