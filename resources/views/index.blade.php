<x-html title="Home">

    <x-html.head description=" This is dashboard">
        <title>Home Page</title>


        @vite(['resources/js/index.js'])

        <script src="https://unpkg.com/lucide@latest"></script>

    </x-html.head>

    <body class="max-w-[100%] tw-overflow-x-hidden">

        <section class="top-vector">
            {{--
            <svg class="position-absolute wave" width="2170" height="1461" viewBox="0 0 2170 1461" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M2162.39 531.023C2166.9 543.783 2169.29 557.046 2169.57 570.813C2169.85 584.581 2168.23 598.462 2164.73 612.457C2161.07 626.713 2156.1 641.423 2149.83 656.588C2143.32 671.266 2135.57 686.27 2126.6 701.598C2117.78 716.666 2108.38 732.271 2098.41 748.411C2088.2 764.064 2077.57 779.994 2066.52 796.199C2055.47 812.405 2044.61 828.724 2033.95 845.156C2022.74 861.622 2012.2 878.298 2002.31 895.185C1992.19 911.585 1982.91 928.31 1974.49 945.359C1966.07 962.408 1958.74 979.391 1952.48 996.31C1946.22 1013.23 1940.96 1030.21 1936.71 1047.26C1932.07 1064.08 1928.25 1080.85 1925.23 1097.58C1922.21 1114.3 1919.88 1130.73 1918.25 1146.87C1916.08 1163.04 1914.05 1178.95 1912.18 1194.6C1910.47 1209.99 1908.9 1225.12 1907.49 1239.99C1905.69 1254.64 1903.73 1268.67 1901.61 1282.08C1899.33 1295.75 1896.63 1308.82 1893.5 1321.29C1889.98 1333.53 1885.83 1345.06 1881.06 1355.87C1876.14 1366.94 1870.4 1377.19 1863.85 1386.61C1856.9 1395.8 1848.95 1404.05 1839.98 1411.36C1830.86 1418.92 1820.65 1425.68 1809.35 1431.63C1798.21 1437.31 1786.05 1442.05 1772.88 1445.86C1759.56 1449.92 1745.43 1453.15 1730.47 1455.56C1715.67 1457.71 1700.16 1459.27 1683.96 1460.25C1667.91 1460.97 1651.42 1461.09 1634.51 1460.6C1617.37 1459.63 1599.79 1458.06 1581.78 1455.89C1564.16 1453.95 1546.27 1451.15 1528.09 1447.48C1509.92 1443.82 1491.58 1439.54 1473.09 1434.64C1454.59 1429.74 1436.21 1424.21 1417.94 1418.04C1399.66 1411.88 1381.69 1405.2 1364.03 1397.99C1345.97 1390.56 1328.15 1382.74 1310.55 1374.53C1292.95 1366.32 1275.39 1357.61 1257.86 1348.39C1240.18 1339.43 1222.46 1330.1 1204.69 1320.4C1187.08 1310.44 1169.2 1300.49 1151.05 1290.56C1133.05 1280.37 1114.85 1270.06 1096.46 1259.64C1077.92 1249.49 1058.99 1239.1 1039.68 1228.49C1020.21 1218.14 1000.39 1207.93 980.233 1197.87C959.836 1187.32 938.941 1177.18 917.548 1167.45C896.154 1157.71 874.145 1148.14 851.519 1138.73C828.893 1129.32 805.499 1120.33 781.337 1111.76C757.175 1103.2 732.396 1094.79 707.002 1086.55C682.147 1078.28 656.676 1070.17 630.589 1062.22C604.891 1054.5 579.192 1046.78 553.493 1039.06C527.254 1031.37 501.361 1023.54 475.814 1015.56C450.267 1007.57 425.066 999.448 400.212 991.175C375.357 982.902 351.195 974.336 327.725 965.478C304.255 956.62 281.553 947.34 259.62 937.637C237.838 927.675 217.095 917.274 197.391 906.434C177.922 896.081 159.526 884.786 142.202 872.55C125.267 860.541 109.792 847.817 95.7771 834.378C82.1506 821.166 69.7906 807.126 58.6972 792.258C47.8396 777.877 38.4845 763.154 30.6319 748.09C22.9313 732.766 16.5811 717.361 11.5811 701.874C7.12133 686.354 3.93579 670.883 2.02449 655.461C0.113203 640.038 -0.329833 624.777 0.695393 609.678C1.72062 594.579 4.33205 579.886 8.52968 565.598C12.5752 551.57 17.9369 537.964 24.6146 524.779C31.6805 511.822 40.1043 499.659 49.8861 488.292C59.668 476.925 70.7318 466.483 83.0776 456.966C95.2712 447.709 109.211 439.474 124.897 432.261C140.042 425.081 156.2 418.843 173.369 413.545C190.927 408.475 209.184 403.99 228.141 400.088C247.099 396.187 266.756 392.869 287.113 390.136C307.47 387.404 328.022 384.784 348.767 382.278C369.512 379.773 390.181 377.397 410.775 375.151C431.908 372.872 452.653 370.366 473.01 367.634C492.979 364.673 512.637 361.356 531.982 357.682C551.327 354.008 569.779 349.635 587.336 344.565C604.894 339.495 621.363 333.614 636.745 326.921C652.278 319.968 666.606 311.96 679.728 302.898C692.697 294.095 704.461 284.237 715.019 273.324C725.965 262.639 735.747 251.272 744.365 239.223C753.37 227.401 761.6 215.125 769.054 202.395C776.896 189.892 784.08 177.178 790.605 164.253C797.519 151.556 804.087 139.004 810.308 126.599C816.529 114.194 822.911 102.406 829.452 91.2341C835.605 79.8355 842.112 69.1674 848.973 59.2297C855.833 49.2921 863.284 40.5718 871.324 33.0691C879.128 25.0794 887.64 18.5506 896.86 13.4828C906.08 8.41501 916.277 4.79186 927.452 2.61336C938.627 0.434871 950.78 -0.29897 963.911 0.411842C976.501 1.15527 990.221 3.08356 1005.07 6.1967C1019.92 9.30983 1035.63 13.6242 1052.2 19.1396C1068.53 24.1681 1085.8 30.2677 1104.01 37.4387C1122.37 44.3499 1141.54 52.0888 1161.54 60.6555C1181.14 68.9951 1201.72 77.9026 1223.26 87.3781C1244.81 96.8535 1267.13 106.783 1290.23 117.167C1313.32 127.552 1337.16 138.017 1361.72 148.563C1386.29 159.109 1411.47 169.493 1437.27 179.714C1463.3 190.422 1490.03 200.838 1517.45 210.961C1544.71 221.344 1572.4 231.451 1600.51 241.282C1628.86 251.6 1657.09 261.674 1685.2 271.505C1713.31 281.336 1741.15 291.183 1768.72 301.047C1796.14 311.17 1822.9 321.082 1849 330.784C1875.34 340.972 1900.67 351.096 1925 361.155C1949.33 371.214 1972.43 381.599 1994.3 392.308C2015.77 402.79 2035.82 413.483 2054.45 424.388C2073.07 435.293 2089.46 446.458 2103.61 457.884C2118.15 469.537 2130.45 481.45 2140.52 493.624C2150.2 505.57 2157.49 518.037 2162.39 531.023Z"
                    fill="#61B000" />
            </svg> --}}

            <picture>
                <source media="(min-width:2560px)"
                    srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-xl.png') }}">
                <source media="(min-width:1200px)"
                    srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-lg.png') }}">
                <source media="(min-width:768px)"
                    srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-md.png') }}">
                <source media="(min-width:576px)"
                    srcset="{{ Vite::asset('resources/images/illus/recruitment/green-curve-sm.png') }}">

                <img class="green-wave" src="{{ Vite::asset('resources/images/illus/recruitment/green-curve-md.png') }}"
                    alt="">
            </picture>

            <svg class="left-circle tw-absolute tw--left-[3%] tw-top-[47vh] -z-[10]" width="171" height="251"
                viewBox="0 0 171 251" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M156 125.5C156 186.527 106.527 236 45.5 236C-15.5275 236 -65 186.527 -65 125.5C-65 64.4725 -15.5275 15 45.5 15C106.527 15 156 64.4725 156 125.5Z"
                    stroke="#404040" stroke-opacity="0.05" stroke-width="30" />
            </svg>


            <svg class="right-circle tw-absolute tw--right-[8%] tw-top-[87vh] -z-[10]" width="145" height="145"
                viewBox="0 0 145 145" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M137.5 72.5C137.5 108.399 108.399 137.5 72.5 137.5C36.6015 137.5 7.5 108.399 7.5 72.5C7.5 36.6015 36.6015 7.5 72.5 7.5C108.399 7.5 137.5 36.6015 137.5 72.5Z"
                    stroke="#61B000" stroke-width="15" />
            </svg>

        </section>

        <header class="top-nav sticky-md-top">
            <nav class="d-flex justify-content-between align-items-center">
                <div class="d-flex site-name home ms-5">
                    <div class="align-content-center">
                        <div class="">
                            <picture class="pri-sm-logo">
                                <source media="(min-width:2560px)" class=""
                                    srcset="{{ Vite::asset('resources/images/logo/Powerlane-513x445.png') }}">
                                <source media="(min-width:1200px)" class=""
                                    srcset="{{ Vite::asset('resources/images/logo/Powerlane-151x131.png') }}">
                                <source media="(min-width:768px)" class=""
                                    srcset="{{ Vite::asset('resources/images/logo/Powerlane-121x105.png') }}">
                                <source media="(min-width:576px)" class=""
                                    srcset="{{ Vite::asset('resources/images/logo/Powerlane-91x79.png') }}">
                                <source media="(max-width:320px)" class=""
                                    srcset="{{ Vite::asset('resources/images/logo/Powerlane-63x53.png') }}">

                                <img src="{{ Vite::asset('resources/images/logo/Powerlane-91x79.png') }}"
                                    alt="">
                            </picture>
                        </div>
                    </div>

                    <x-nav-link href="/" :active="request()->is('/')" class="no-hover">
                        <h1 class=" fs-2">Powerlane</h1>
                    </x-nav-link>
                </div>
                <div class="d-flex align-items-center fw-bold">
                    <x-nav-link href="/about-us" :active="request()->is('about-us')">About</x-nav-link>
                    <x-nav-link href="/contact-us" :active="request()->is('contact-us')">Contact</x-nav-link>
                    <x-nav-link href="/applicants/apply" class="btn btn-secondary bg-white text-primary"
                        :active="request()->is('applicants/apply')">Sign Up</x-nav-link>

                </div>
            </nav>
        </header>



        <main class="container-fluid tw-px-[5rem]">

            <section class="first-section d-flex tw-min-h-[90vh] ">
                <div class="left col-12 col-md-6 align-content-center">
                    <h3 class="text-primary fs-1 fw-bold tw-tracking-wide">
                        We are hiring!
                    </h3>
                    <p class="fs-4 fw-medium">
                        Exciting career opportunities available.
                        <br>
                        Explore our open job positions today.
                    </p>

                </div>

                <div class="right col-12 col-md-6 ">
                    <div class="tw-relative tw-translate-x-6 tw-translate-y-10">
                        <div class="box-icon">
                            <i data-lucide="search"></i>
                        </div>
                        <picture class="sapien">
                            <source media="(min-width:1400px)" class=""
                                srcset="{{ Vite::asset('resources/images/illus/sapiens-1-2044x1816.png') }}">
                            <source media="(min-width:1200px)" class=""
                                srcset="{{ Vite::asset('resources/images/illus/sapiens-1-1022x908.png') }}">
                            <source media="(min-width:768px)" class=""
                                srcset="{{ Vite::asset('resources/images/illus/sapiens-1-511x454.png') }}">
                            <source media="(min-width:576px)" class=""
                                srcset="{{ Vite::asset('resources/images/illus/sapiens-1-384x341.png') }}">
                            <img src="{{ Vite::asset('resources/images/illus/sapiens-1-1022x908.png') }}"
                                class="" alt="">
                        </picture>
                    </div>
                    <div class="box-icon">
                        <i data-lucide="briefcase"></i>
                    </div>
                </div>
            </section>

            <section class="second-section">
                <x-input.search-group container_class="col-8 col-md-4" class="justify-content-center w-100">
                    <x-slot:right_icon>
                        <i data-lucide="search"></i>
                    </x-slot:right_icon>
                    <x-input.search type="search" placeholder="Search job titles or companies"></x-input.search>
                </x-input.search-group>
                <div class="tw-px-[5rem] tw-pt-[2.5rem] tw-pb-[1rem]">
                    <em>
                        Currently <span></span> <span>jobs</span> available
                    </em>
                </div>
                <section class="job-listing  d-flex tw-px-[5rem] tw-gap-12 ">
                    <sidebar class="nav nav-tabs col-5 " role="tablist">

                        <?php
                    for ($i = 0; $i < 20; $i++) {
                    ?>

                        <li class="card nav-item ps-0 " role="presentation">
                            <button class="nav-link d-flex flex-row tw-gap-x-6" id="{{ $i }}-tab"
                                data-bs-toggle="tab" data-bs-target="#{{ $i }}-tab-pane" role="tab">
                                <div class="col-4 pt-3 px-2 ">
                                    <img src="http://placehold.it/74/74" alt="">
                                </div>
                                <div class="col-7 text-start">
                                    <header>
                                        <hgroup>
                                            <h4 class="card-title text-black mb-0">Card title</h4>
                                            <p class="fs-4 text-primary">Card title</p>
                                        </hgroup>
                                    </header>
                                    <div class="">

                                        <div class="card-text text-black">content.</div>
                                        <div class="card-text text-black">content.</div>
                                    </div>
                                </div>
                            </button>


                        </li>
                        <?php
                        }
                    ?>
                    </sidebar>
                    <article class="job-view tab-content col-6">
                        <div class="job-content tab-pane fade show active card border-0 bg-secondary w-100 "
                            id="#1-tab-pane" role="tabpanel" aria-labelledby="-tab">
                            <div class="d-flex">
                                <header>
                                    <hgroup>
                                        <h4 class="card-title text-primary fw-bold mb-0">Job Position</h4>
                                        <p class="fs-6 text-black ">Job Location</p>
                                    </hgroup>
                                    <a href="" hreflang="en-PH" class="btn btn-primary mt-1 mb-4"
                                        role="navigation" aria-label="Apply">Apply <span><i
                                                data-lucide="external-link"></i></span></a>

                                    <p class="job-descr card-text">
                                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Blanditiis
                                        possimus expedita ipsum atque magni laboriosam vel veritatis, suscipit eum
                                        quam quaerat cupiditate veniam voluptatem. Cum pariatur quisquam totam vero
                                        natus?
                                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Blanditiis
                                        possimus expedita ipsum atque magni laboriosam vel veritatis, suscipit eum
                                        quam quaerat cupiditate veniam voluptatem. Cum pariatur quisquam totam vero
                                        natus?
                                    </p>
                                    <button href=""
                                        class="bg-transparent border border-0 text-decoration-underline text-black ps-0">
                                        Show More <span><i data-lucide="chevron-down"></i></span>
                                    </button>
                                </header>
                                <div>
                                    <button class="bg-transparent border border-0">
                                        <i data-lucide="more-vertical"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                </section>

            </section>

        </main>

        {{-- <x-html.test-elements></x-html.test-elements> --}}

        <x-employee.footer></x-employee.footer>

    </body>

</x-html>
