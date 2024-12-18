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

new GlobalListener('click', document, `[aria-label="Application Additional Details"]:not(:has(*:user-invalid))+section div[wire\\:click="validateNow"]`, () => {
    if (JSON.parse(sessionStorage.getItem('applicant'))?.region) return;

    const locationService = new LocationService();
    locationService.getUserRegion((data, region) => {

        let applicantData = JSON.parse(sessionStorage.getItem('applicant')) || {};

        // Append new data
        applicantData.region = region;

        sessionStorage.setItem('applicant', JSON.stringify(applicantData));
    });
});

new GlobalListener('change', document, `#present_region`, (e) => {
    document.querySelector(`${applicationForm} #present_province`).value = '';
    document.querySelector(`${applicationForm} #present_city`).value = '';
    document.querySelector(`${applicationForm} #present_barangay`).value = '';
});
new GlobalListener('change', document, `#present_province`, (e) => {
    document.querySelector(`${applicationForm} #present_city`).value = '';
    document.querySelector(`${applicationForm} #present_barangay`).value = '';
});
new GlobalListener('change', document, `#present_city`, (e) => {
    document.querySelector(`${applicationForm} #present_barangay`).value = '';
});

new GlobalListener('change', document, `#permanent_region`, (e) => {
    if (document.querySelector(`${applicationForm} #sameAddressCheck`).checked) return;

    document.querySelector(`${applicationForm} #permanent_province`).value = '';
    document.querySelector(`${applicationForm} #permanent_city`).value = '';
    document.querySelector(`${applicationForm} #permanent_barangay`).value = '';
});
new GlobalListener('change', document, `#permanent_province`, (e) => {
    if (document.querySelector(`${applicationForm} #sameAddressCheck`).checked) return;
    document.querySelector(`${applicationForm} #permanent_city`).value = '';
    document.querySelector(`${applicationForm} #permanent_barangay`).value = '';
});
new GlobalListener('change', document, `#permanent_city`, (e) => {
    if (document.querySelector(`${applicationForm} #sameAddressCheck`).checked) return;
    document.querySelector(`${applicationForm} #permanent_barangay`).value = '';
});

function setOptions(presentSelect, permanentSelect) {

    let presentSelectVal = presentSelect.value;
    let optionExists = Array.from(permanentSelect.options).some(option => option.value === presentSelectVal);

    if (!optionExists) {
        createSelectOptions(permanentSelect, presentSelectVal, presentSelect.options[presentSelect.selectedIndex].text)
    }

}

function createSelectOptions(element, val, text) {
    let newOption = document.createElement('option');
    newOption.value = val;
    newOption.text = text;
    element.add(newOption);
}

new GlobalListener('input', document, `${applicationForm} #sameAddressCheck`, (e) => {

    let presentRegionSelect = document.querySelector(`${applicationForm} #present_region:valid`);
    let presentProvinceSelect = document.querySelector(`${applicationForm} #present_province:valid`);
    let presentCitySelect = document.querySelector(`${applicationForm} #present_city:valid`);
    let presentBarangaySelect = document.querySelector(`${applicationForm} #present_barangay`);
    let presentAddressInput = document.querySelector(`${applicationForm} #present_address:valid`);

    if (!(presentRegionSelect && presentProvinceSelect && presentCitySelect && presentBarangaySelect && presentAddressInput)) {
        e.target.checked = false;
        e.preventDefault();
        e.stopPropagation();
    }

    if (!e.target.checked) {
        return;
    }

    let component = Livewire.find(e.target.getAttribute('data-comp-id'))

    component.dispatch('useSameAsPresentAddress');

    let permanentRegionSelect = document.querySelector(`${applicationForm} #permanent_region`);
    let permanentProvinceSelect = document.querySelector(`${applicationForm} #permanent_province`);
    let permanentCitySelect = document.querySelector(`${applicationForm} #permanent_city`);
    let permanentBarangaySelect = document.querySelector(`${applicationForm} #permanent_barangay`);
    let permanentAddressInput = document.querySelector(`${applicationForm} #permanent_address`);

    setOptions(presentRegionSelect, permanentRegionSelect)
    setOptions(presentProvinceSelect, permanentProvinceSelect)
    setOptions(presentCitySelect, permanentCitySelect)
    setOptions(presentBarangaySelect, permanentBarangaySelect)

    setTimeout(() => {
        permanentRegionSelect.value = presentRegionSelect.value
        permanentProvinceSelect.value = presentRegionSelect.value
        permanentCitySelect.value = presentRegionSelect.value
        permanentBarangaySelect.value = presentRegionSelect.value
        permanentAddressInput.value = presentAddressInput.value

        component.set('address.permanentRegion', presentRegionSelect.value);
        component.set('address.permanentProvince', presentRegionSelect.value);
        component.set('address.permanentCity', presentRegionSelect.value);
        component.set('address.permanentBarangay', presentRegionSelect.value);
        component.set('address.permanentAddress', presentAddressInput.value);

        Array.from(permanentRegionSelect.options).forEach(option => {
            option.selected = option.value === presentRegionSelect.value;
        });
        Array.from(permanentProvinceSelect.options).forEach(option => {
            option.selected = option.value === presentProvinceSelect.value;
        });
        Array.from(permanentCitySelect.options).forEach(option => {
            option.selected = option.value === presentCitySelect.value;
        });
        Array.from(permanentBarangaySelect.options).forEach(option => {
            option.selected = option.value === presentBarangaySelect.value;
        });

        permanentRegionSelect.dispatchEvent(new Event('change'));
        permanentProvinceSelect.dispatchEvent(new Event('change'));
        permanentCitySelect.dispatchEvent(new Event('change'));
        permanentBarangaySelect.dispatchEvent(new Event('change'));

    }, 0);

});

document.addEventListener('livewire:init', () => {

    Livewire.on('same-as-present-address', (event) => {
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

document.addEventListener('livewire:init', () => {

    Livewire.on('applicant-application-received', (event) => {
        bootstrap.Modal.getOrCreateInstance('#application-email-alert').show();
    });

});
