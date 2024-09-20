@php
    $nonce = csp_nonce();
@endphp

@extends('layout.guest-primary', ['description' => 'Guest Layout', 'nonce' => $nonce])

@section('head')
    <title>Apply</title>
    <script src="build/assets/nbp.min.js" defer></script>
    @vite(['resources/js/login.js', 'resources/js/dropzone.config.js', 'resources/js/progress-bar.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.css">
    <style nonce="{{ $nonce }}">
        /* General Styles */
        .dropzone-container {
            position: relative;
            display: inline-block;
            width: 100%;
            height: 250px;
        }

        .dropzone {
            width: 100%;
            height: 100%;
            border: 2px dashed #007bff;
            position: relative;
            top: 0;
            left: 0;
            z-index: 1;
            text-align: center;
            flex-direction: column;
            background: rgba(97, 176, 0, 0.05);
        }

        .dz-message {
            position: relative;
            z-index: 2;
            text-align: center;
            display: flex;
            flex-direction: column;
        }

        /* Hide all sections by default */
        .form-step {
            display: none;
        }

        /* Show the first step by default */
        .form-step.active {
            display: block;
        }

        /* Specific Styles for Labels, Form, and Buttons */
        .profile-pic {
            display: block;
            width: 126px;
            height: 126px;
            background: #F6F6F6;
            padding: 20px;
            border: 1.5px solid #61B00080;
            margin: 40px auto;
            border-radius: 50%;
            text-align: center;
        }

        .upload {
            display: none;
        }

        .prev {
            color: rgba(97, 176, 0, 1);
            font-family: Figtree;
            font-size: 17px;
            font-weight: 700;
            text-align: center;
            border: none;
            background: none;
            margin-right: 70%;
        }

        .prev:hover {
            color: white;
            background: rgba(97, 176, 0, 1);
            font-family: Figtree;
            font-size: 17px;
            font-weight: 700;
            text-align: center;
            padding: 5px 5px 8px 5px;
            border-radius: 5px;
        }

        button {
            margin-top: 40px;
        }

        .personal-details {
            padding: 20px;
            background-color: white;
        }

        .field {
            font-family: Figtree;
            font-size: 12px;
            font-weight: 400;
            line-height: 14.4px;
            margin-top: 20px;
            margin-bottom: 15px
        }

        .in {
            width: 100%;
            height: 45px;
            border: 1px solid lightgray;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .inp {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .fvicon {
            display: block;
            width: 15px;
            position: relative;
            left: 42%;
            top: 23px;
        }

        .confirmation {
            display: block;
        }

        .res {
            width: 90%;
        }

        .resume {
            width: 303px;
            height: 319px;
            border: 1px solid lightgray;
            border-radius: 15px;
            text-align: justify;
        }

        .conf {
            position: absolute;
            left: 50%;
            top: 50%;
        }

        /* Progress bar container */
        .progress-bar {
            display: flex;
            flex: 1;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            height: 100%;
            padding: 0;
            position: relative;
        }

        /* Individual step container */
        .step {
            flex: 1;
            text-align: center;
            position: relative;
            display: none;
            /* Hide all steps by default */
            flex-direction: column;
            align-items: center;
        }

        .step.active {
            display: flex;
            /* Show only active step */
        }

        /* Bullet (circle) styles */
        .bullet {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border: 2px solid lightgray;
            border-radius: 50%;
            background: white;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
            transition: background 0.4s ease;
        }

        /* Active step styles */
        .step.active .bullet {
            border-color: rgba(97, 176, 0, 1);
        }

        .step.active p {
            color: rgba(97, 176, 0, 1);
        }

        /* Completed step styles */
        .step.completed .bullet {
            background-color: rgba(97, 176, 0, 1);
            border-color: rgba(97, 176, 0, 1);
        }

        .step.completed .bullet span {
            display: none;
        }

        .step.completed .check {
            display: block;
            color: white;
            font-size: 18px;
            line-height: 40px;
            position: relative;
            top: 0;
            left: 50%;
            transform: translate(-50%, 0);
        }

        /* Adjust the checkmark size */
        .check {
            font-size: 18px;
            color: white;
            display: none;
        }

        /* Show checkmark when step is completed */
        .step.completed .check {
            display: block;
        }
    </style>
@endsection

@section('content')
    <section class="first-section">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-xl-7 col-lg-8 col-md-10 col-sm-12">
                    <div class="card border-0 shadow-sm rounded-4 my-5">
                        <div class="card-body p-4 p-sm-5">
                            <div class="text-center mb-4">
                                <h1 class="h3 mb-3 fw-normal" style="color: rgba(97, 176, 0, 1);">Applying for:</h1>
                                <p class="mb-0">Please upload and fill in the necessary details</p>
                            </div>
                            <div class="progress-bar">
                                <div class="step active">
                                    <div class="check fas fa-check"></div>
                                    <div class="bullet">
                                        <span>1</span>
                                    </div>
                                    <p class="bob">Upload Resume</p>
                                </div>
                                <div class="step">
                                    <div class="check fas fa-check"></div>
                                    <div class="bullet">
                                        <span>2</span>
                                    </div>
                                    <p class="bob">Personal Details</p>
                                </div>
                                <div class="step">
                                    <div class="check fas fa-check"></div>
                                    <div class="bullet">
                                        <span>3</span>
                                    </div>
                                    <p class="bob">Final Information</p>
                                </div>
                            </div>
                            <div class="check fas fa-check"></div>
                        </div>
                    </div>
                    <form class="needs-validation" novalidate="" id="upload-form">
                        <!-- Step 1: Upload Resume -->
                        <div class="form-step active" id="step-1">
                            <div class="form-group mb-3 dropzone-container">
                                <label for="drop-box" id="dropzone" class="dropzone clickable-area">
                                    <img style="width= 15px" src="{{ Vite::asset('resources/images/icons/adddocs.png') }}"
                                        alt="adddocs">
                                    <div class="dz-default dz-message">
                                        <span>Drag and drop or <strong>Choose a file</strong> to upload</span>
                                        <span class="pdf-50">PDF File Only | Max size is 50mb</span>
                                    </div>
                                </label>
                                <input class="upload" type="file" accept="image/jpeg, image/jpg, image/png"
                                    id="drop-box">
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary next-btn">Next</button>
                            </div>
                        </div>

                        <!-- Step 2: User Information -->
                        <div class="form-step" id="step-2">
                            <label for="input-file" class="profile-pic">
                                <img src="{{ Vite::asset('resources/images/icons/li_camera.png') }}" alt="Upload Pic"
                                    class="profpic">
                                <p2> Upload a Photo </p2>
                            </label>
                            <input class="upload" type="file" accept="image/jpeg, image/png, image/jpg" id="input-file">

                            <div class="personal-details">
                                <div class="field">Last Name</div>
                                <input class="in" type="text">

                                <div class="d-flex">
                                    <div class="me-2" style="flex: 1;">
                                        <div class="field">First Name</div>
                                        <input class="inp" type="text">
                                    </div>
                                    <div style="flex: 1;">
                                        <div class="field">Middle Name</div>
                                        <input class="inp" type="text">
                                    </div>
                                </div>

                                <div class="d-flex mt-2">
                                    <div class="me-2" style="flex: 1;">
                                        <div class="field">Sex at Birth</div>
                                        <select class="inp">
                                            <option value="Select">Select</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div style="flex: 1;">
                                        <div class="field">Birthdate</div>
                                        <input type="date" class="inp">
                                    </div>
                                </div>

                                <div class="confirmation mt-3">
                                    <button type="button" class="prev prev-btn">← Previous</button>
                                    <button type="button" class="btn btn-primary next-btn">Next</button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Overview -->
                        <div class="form-step" id="step-3">
                            <div class="res">
                                <img src="{{ Vite::asset('resources/images/icons/fullscreenicon.png') }}" alt="fsicon"
                                    class="fvicon">
                                <img src="{{ Vite::asset('resources/images/illus/spoofimg.avif') }}" alt="spoofimg"
                                    class="resume">
                            </div>
                            <div class="conf">
                                <div class="field">Email Address</div>
                                <!-- User email output -->
                                <div class="field">Full Name</div>
                                <!-- User full name output -->
                                <div class="field">Birthdate</div>
                                <!-- User birthdate output -->
                                <div class="field">Sex at Birth</div>
                                <!-- User sex at birth output -->
                            </div>

                            <div class="confirmation mt-3">
                                <button type="button" class="prev prev-btn">← Previous</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        </div>
        </div>
    </section>

    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            const totalSteps = 3;

            const formSteps = document.querySelectorAll('.form-step');
            const nextBtns = document.querySelectorAll('.next-btn');
            const prevBtns = document.querySelectorAll('.prev-btn');

            nextBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentStep < totalSteps) {
                        formSteps[currentStep - 1].classList.remove('active');
                        formSteps[currentStep].classList.add('active');
                        currentStep++;
                    }
                });
            });

            prevBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentStep > 1) {
                        formSteps[currentStep - 1].classList.remove('active');
                        formSteps[currentStep - 2].classList.add('active');
                        currentStep--;
                    }
                });
            });
        });
    </script>
@endsection
