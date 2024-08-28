<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Powerlane Application</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .progress-bar {
            width: 500px;
            background-color: var(--Main-Green, #61B000);
            border-radius: 10px;
            margin: 20px auto;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 20px;
            background-color: var--gray #3331B000;);
            border-radius: 10px;
            transition: width 0.5s ease-in-out;
        }

        .upload-area {
            width: 300px;
            height: 150px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            margin: 20px auto;
            position: relative;
            overflow: hidden;
        }

        .upload-area .upload-icon {
            font-size: 4em;
            color: #ccc;
        }

        .upload-area .upload-text {
            font-size: 1.2em;
            color: #ccc;
            margin-left: 10px;
        }

        .upload-area input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* Style for upload progress */
        .upload-progress {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background-color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            display: none; /* Hide by default */
        }

        .upload-progress .progress-text {
            font-size: 0.8em;
            color: #333;
        }

        /* Style for uploaded file name */
        .uploaded-file-name {
            margin-top: 10px;
            font-size: 1em;
            color: #333;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-green-500 py-4">
        <div class="container mx-auto flex items-center justify-between">
            <a href="#" class="flex items-center">
                <picture>
                    <!-- Use Vite directive to include assets -->
                    <source media="(min-width:2560px)" srcset="{{ asset('images/logo/Powerlane-65x53.png') }}">
                    <source media="(min-width:1200px)" srcset="{{ asset('images/logo/Powerlane-63x53.png') }}">
                    <img src="{{ asset('images/logo/Powerlane-63x53.png') }}" alt="Powerlane Logo" class="mr-2">
                </picture>
                <span class="text-white font-bold text-xl">Powerlane</span>
            </a>
        </div>
    </header>

    <main class="container mx-auto mt-10">
        <div class="bg-white p-6 rounded-md shadow-md">
            <h2 class="text-2xl font-bold mb-4">Applying for: Accountant</h2>
            <p class="text-gray-600 mb-6">Please upload and fill in the necessary details.</p>

            <div class="progress-bar">
                <div class="progress-bar-fill" style="width: 33.33%;"></div>
            </div>

            <div class="flex justify-center mb-6">
                <div class="flex items-center mr-6">
                    <div class="rounded-full bg-green-500 w-10 h-10 flex items-center justify-center text-white font-bold">1</div>
                    <p class="ml-2">Upload Resume</p>
                </div>
                <div class="flex items-center mr-6">
                    <div class="rounded-full bg-gray-300 w-10 h-10 flex items-center justify-center text-gray-600 font-bold">2</div>
                    <p class="ml-2">Personal Details</p>
                </div>
                <div class="flex items-center">
                    <div class="rounded-full bg-gray-300 w-10 h-10 flex items-center justify-center text-gray-600 font-bold">3</div>
                    <p class="ml-2">Final Information</p>
                </div>
            </div>

            <div class="upload-area" id="uploadArea">
                <div class="flex items-center">
                    <i class="fas fa-file-upload upload-icon"></i>
                    <p class="upload-text" id="uploadText">Drag & Drop or Choose a file to upload</p>
                </div>
                <input type="file" id="fileInput" accept=".pdf">
                <div class="upload-progress" id="uploadProgress">
                    <span class="progress-text">Uploading... <span id="uploadPercentage">0%</span></span>
                </div>
                <p class="uploaded-file-name" id="uploadedFileName"></p> 
            </div>

            <p class="text-gray-600 text-sm mt-2">PDF File only | Max size is 50mb</p>

            <div class="mt-6 text-center">
                <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md" id="next-button" disabled>Next</button>
            </div>
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

    <script>
        $(document).ready(function() {
            $("#fileInput").on("change", function() {
                if (this.files && this.files[0]) {
                    // Get the file name
                    var fileName = this.files[0].name;
                    $("#uploadedFileName").text("Uploaded file: " + fileName); // Display the file name
                    $("#uploadText").text("File uploaded: " + fileName); // Update the text 

                    // Start simulated upload progress
                    var percentage = 0;
                    var uploadInterval = setInterval(function() {
                        percentage += 10; // Increase percentage by 10% every second
                        $("#uploadPercentage").text(percentage + "%");
                        $("#uploadProgress").show(); // Show progress bar

                        if (percentage >= 100) {
                            clearInterval(uploadInterval); // Stop the interval
                            $("#uploadProgress").hide(); // Hide progress bar
                            $("#next-button").prop("disabled", false); // Enable Next button
                        }
                    }, 1000); // Update progress every 1 second
                }
            });

            $("#next-button").click(function() {
                window.location.href = '/applicantinfo';
            });
        });
    </script>

    <script src="https://kit.fontawesome.com/your-fontawesome-kit-id.js" crossorigin="anonymous"></script> 
</body>
</html>