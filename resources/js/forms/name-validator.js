import debounce from 'debounce-script';
import InputValidator, { setInvalidMessage, setFormDirty } from 'input-validator-script';
import addGlobalListener, { GlobalListener } from 'globalListener-script';
import './name-validate-rule.js';

export default class NameValidator {
    constructor(inputSelector, validator, parent = document) {
        this.inputSelector = inputSelector;
        this.validator = validator;
        this.parent = parent;
    }

    // Validate individual input element
    validateElement(element) {
        const isValid = this.validator.validate(element, setInvalidMessage);
        if (!isValid) {
            element.classList.add('is-invalid');
        } else {
            element.classList.remove('is-invalid');
        }
        return isValid;
    }

    validateName(inputSelector, parent = document) {
        const element = parent.querySelector(this.inputSelector);
        return this.validateElement(element);
    }

    // Initialize debounced validation on input
    initValidation(callback, resultRef) {
        const debouncedValidation = debounce((event) => {

            event.target.classList.add('is-dirty');

            const isValid = this.validateElement(event.target);
            try {
                resultRef = isValid;
            } catch (error) {

            }
            callback();
        }, 500);

        addGlobalListener('input', this.parent, this.inputSelector, debouncedValidation);
    }

}
