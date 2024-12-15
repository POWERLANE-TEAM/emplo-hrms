import '../script.js';
import initLucideIcons from '../icons/lucide.js';
import addGlobalScrollListener, { documentScrollPosY } from 'global-scroll-script';
import addGlobalListener, { GlobalListener } from 'globalListener-script';
import InputValidator, { setInvalidMessage } from '../forms/input-validator.js';
import initEmailValidation, { validateEmail } from '../forms/email-validation.js';
import initIframeFullScreener from 'iframe-full-screener-script';
import NameValidator from 'name-validator-script';
import { LAST_NAME_VALIDATION, MIDDLE_NAME_VALIDATION, FIRST_NAME_VALIDATION } from 'name-validate-rule';
import 'websocket-script';
import LocationService from '../utils/location.js';
// import './livewire.js'


document.addEventListener('livewire:navigate', () => {
    Livewire.hook('morph.removed', ({ el, component }) => {
        initLucideIcons();
    })
    setTimeout(() => {
        initLucideIcons();
    }, 0);
});

document.addEventListener('livewire:init', () => {
    LivewireFilePond.registerPlugin(FilePondPluginPdfPreview);
});

initIframeFullScreener('apply-resume-preview');


let hasUnsavedChanges = false;
let logoutCallback = null;

try {
    Echo.private(`applicant.applying.${AUTH_BROADCAST_ID}`)
        //  I think livewire is already hnadling the listener for this event
        .listen('Guest.ResumeParsed', (event) => {
            // maybe show a toast message
        })
} catch (error) {

}

LAST_NAME_VALIDATION.attributes.type = 'list';
FIRST_NAME_VALIDATION.attributes.type = 'list';
MIDDLE_NAME_VALIDATION.attributes.type = 'list';

// On input client validations
const applicationForm = 'form[id="application-wizard-form"]';

const firstNameValidator = new NameValidator(`${applicationForm} input[name="applicant.name.firstName"]`, new InputValidator(FIRST_NAME_VALIDATION));
const middleNameValidator = new NameValidator(`${applicationForm} input[name="applicant.name.middleName"]`, new InputValidator(MIDDLE_NAME_VALIDATION));
const lastNameValidator = new NameValidator(`${applicationForm} input[name="applicant.name.lastName"]`, new InputValidator(LAST_NAME_VALIDATION));

firstNameValidator.initValidation(null, null);
middleNameValidator.initValidation(null, null);
lastNameValidator.initValidation(null, null);

initEmailValidation(`${applicationForm} input[name="applicant.email"]`, null, null);

const passwordConfirmPaste = new GlobalListener('click', document, `[aria-label="Application Additional Details"]:not(:has(*:user-invalid))+section div[wire\\:click="validateNow"]`, () => {
    if (JSON.parse(sessionStorage.getItem('applicant'))?.region) return;

    const locationService = new LocationService();
    locationService.getUserRegion((data, region) => {
        console.log('Callback Region:', data, region);

        let applicantData = JSON.parse(sessionStorage.getItem('applicant')) || {};

        // Append new data
        applicantData.region = region;

        sessionStorage.setItem('applicant', JSON.stringify(applicantData));
    });
});

new GlobalListener('click', document, `#present_region`, (e) => {
    console.log('Region:', e.target.value);
    // setRegionInput(e.target)
});

new GlobalListener('input', document, `${applicationForm} #sameAddressCheck`, (e) => {

    // if (e.target.checked) {
    console.log('Same input');


    // }

});

document.addEventListener('livewire:init', () => {
    console.log('Same init:');
    Livewire.on('same-as-present-address', (event) => {
        console.log('Same Address:', event);
        setTimeout(() => {
            document.querySelector(`${applicationForm} #permanent_region`).value = document.querySelector(`${applicationForm} #present_region`).value;
            document.querySelector(`${applicationForm} #permanent_province`).value = document.querySelector(`${applicationForm} #present_province`).value;
            document.querySelector(`${applicationForm} #permanent_city`).value = document.querySelector(`${applicationForm} #present_city`).value;
            document.querySelector(`${applicationForm} #permanent_barangay`).value = document.querySelector(`${applicationForm} #present_barangay`).value;
        }, 0);
    });

});

function setRegionInput(selector) {
    let applicantData = getLocalSession('applicant');

    if (applicantData.region) {
        let selectElement;
        try {
            selectElement = document.querySelector(selector);
        } catch (error) {
            selectElement = selector;
        }
        const options = selectElement.options;
        const region = applicantData.region.toLowerCase();

        for (let i = 0; i < options.length; i++) {
            if (options[i].text.toLowerCase().includes(region)) {
                selectElement.selectedIndex = i;
                break;
            }
        }
    }
}

function getLocalSession(key) {
    try {
        return JSON.parse(sessionStorage.getItem(key)) || {};
    } catch (error) {
        return null;
    }
}


