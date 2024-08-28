<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Powerlane Application - Personal Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-green-500 py-4">
        <div class="container mx-auto flex items-center justify-between">
            <a href="#" class="flex items-center">
                <img src="{{ asset('images/logo/Powerlane-63x53.png') }}" alt="Powerlane Logo" class="mr-2">
                <span class="text-white font-bold text-xl">Powerlane</span>
            </a>
        </div>
    </header>

    <main class="container mx-auto mt-10">
        <div class="bg-white p-6 rounded-md shadow-md">
            <h2 class="text-2xl font-bold mb-4">Applying for: Accountant</h2>
            <p class="text-gray-600 mb-6">Please fill in your personal details below.</p>

            <div class="progress-bar">
                <div class="progress-bar-fill" style="width: 66.66%;"></div>
            </div>

            <div class="flex justify-center mb-6">
                <div class="flex items-center mr-6">
                    <div class="rounded-full bg-green-500 w-10 h-10 flex items-center justify-center text-white font-bold">1</div>
                    <p class="ml-2">Upload Resume</p>
                </div>
                <div class="flex items-center mr-6">
                    <div class="rounded-full bg-green-500 w-10 h-10 flex items-center justify-center text-white font-bold">2</div>
                    <p class="ml-2">Personal Details</p>
                </div>
                <div class="flex items-center">
                    <div class="rounded-full bg-gray-300 w-10 h-10 flex items-center justify-center text-gray-600 font-bold">3</div>
                    <p class="ml-2">Final Information</p>
                </div>
            </div>

            <form action="submit-personal-details.php" method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="first_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" id="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" name="phone" id="phone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="birthdate" class="block text-sm font-medium text-gray-700">Birthdate</label>
                        <input type="date" name="birthdate" id="birthdate" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" name="address" id="address" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md">Submit</button>
                </div>
            </form>
        </div>
    </main>

    <footer class="bg-green-500 py-4 mt-10">
        <div class="container mx-auto text-white flex justify-between items-center">
            <div>
                <img src="https://dummyimage.com/40x40/000/fff" alt="Powerlane Logo" class="mr-2">
                <p class="text-sm">Powerlane Resources Inc. is a manpower company that is stationed in Santa Rosa, Laguna.</p>
            </div>
            <div class="flex">
                <div class="mr-8">
                    <h4 class="font-bold text-sm mb-2">Company</h4>
                    <ul class="text-sm">
                        <li><a href="#" class="hover:underline">About Us</a></li>
                        <li><a href="#" class="hover:underline">Careers</a></li>
                        <li><a href="#" class="hover:underline">Background</a></li>
                    </ul>
                </div>
                <div class="mr-8">
                    <h4 class="font-bold text-sm mb-2">Legal Links</h4>
                    <ul class="text-sm">
                        <li><a href="#" class="hover:underline">Terms of Use</a></li>
                        <li><a href="#" class="hover:underline">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="flex items-center">
                    <img src="https://dummyimage.com/24x24/000/fff&text=📞" alt="Phone Icon" class="mr-2">
                    <p class="text-sm">pri.recruitment@powerlane.net</p>
                    <p class="text-sm">09173090481 / 09987922305</p>
                </div>
                <div class="ml-8">
                    <h4 class="font-bold text-sm mb-2">Language</h4>
                    <select class="bg-white text-gray-700 font-bold py-2 px-4 rounded-md">
                        <option value="en">English</option>
                    </select>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>