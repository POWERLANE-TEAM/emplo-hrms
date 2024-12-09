export const FIRST_NAME_VALIDATION = {
    clear_invalid: true,
    trailing: {
        '-+': '-',    // Replace consecutive dashes with a single dash
        '\\.+': '.',  // Replace consecutive periods with a single period
        ' +': ' ',    // Replace consecutive spaces with a single space
        '\\\'+': '\'',    // Replace consecutive aposthrophe with a single aposthrophe
    },
    attributes: {
        type: 'text',
        // pattern: /^[\p{L} \'-]+$/u,
        pattern: /^[A-Za-zÑñ '\-]+$/,
        required: true,
        max_length: 191,
    },
    // customMsg: {
    //     required: true,
    //     max_length: '',
    // },
    errorFeedback: {
        required: 'First name is required.',
        max_length: 'First name cannot be more than 255 characters.',
        pattern: 'Invalid first name.',
        typeMismatch: 'Invalid first name.',
        trailing: 'Consecutive repeating characters not allowed.',
    }
}

export const MIDDLE_NAME_VALIDATION = { ...FIRST_NAME_VALIDATION };
export const LAST_NAME_VALIDATION = { ...FIRST_NAME_VALIDATION };

MIDDLE_NAME_VALIDATION.attributes = {
    type: 'text',
    // pattern: /^[\p{L} \'-]+$/u,
    pattern: /^[A-Za-zÑñ '\-]+$/,
    max_length: 191,
};

MIDDLE_NAME_VALIDATION.errorFeedback = {
    max_length: 'Middle name cannot be more than 255 characters.',
    pattern: 'Invalid middle name.',
    typeMismatch: 'Invalid middle name.',
    trailing: 'Consecutive repeating characters not allowed.',
};

LAST_NAME_VALIDATION.errorFeedback = {
    required: 'Last name is required.',
    max_length: 'Last name cannot be more than 255 characters.',
    pattern: 'Invalid last name.',
    typeMismatch: 'Invalid last name.',
    trailing: 'Consecutive repeating characters not allowed.',
};
