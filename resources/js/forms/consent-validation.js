import addGlobalListener from 'globalListener-script';
import InputValidator, { setInvalidMessage, setFormDirty } from './input-validator.js';
import debounce from 'debounce-script';

export default class ConsentValidator {
    constructor(inputSelector, validator, resultRef, callback = null, parent = document) {
        this.inputSelector = inputSelector;
        this.validator = validator;
        this.parent = parent;
        this.callback = callback;
        this.initValidation(resultRef);
    }

    validateElement(element) {
        const isValid = this.validator.validate(element, setInvalidMessage);
        if (!isValid) {
            element.classList.add('is-invalid');
        } else {
            element.classList.remove('is-invalid');
        }
        return isValid;
    }

    validateThis(parent = document) {
        const element = parent.querySelector(this.inputSelector);
        return this.validateElement(element);
    }

    // Initialize debounced validation on input
    initValidation(resultRef) {
        const debouncedValidation = debounce((event) => {

            setFormDirty(event);

            const isValid = this.validateElement(event.target);

            try {
                resultRef = isValid;
            } catch (error) {

            }
            this.callback?.();
        }, 50);

        addGlobalListener('input', this.parent, this.inputSelector, debouncedValidation);
    }

}
