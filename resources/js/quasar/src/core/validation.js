import { ref, computed } from 'vue';

export const validation = {
	__errorsArray: ref({}),

	error: (field) => {
		if (!field) return null;
		return computed(() => {
			if (field.match(/\.\*$/)) {
				let regex = new RegExp(field + '(?:$|..*$)', 'g');
				let result = null;
				Object.keys(validation.__errorsArray.value).map((errorField) => {
					if (errorField.match(regex)) {
						result = true;
						return false;
					}
				});
				return result;
			} else {
				return validation.__errorsArray.value.hasOwnProperty(field) || null;
			}
		}).value;
	},

	errorMessage: (field) => {
		if (!field) return '';
		return computed(() => {
			if (field.match(/\.\*$/)) {
				let regex = new RegExp(field + '(?:$|..*$)', 'g');
				let result = null;
				Object.keys(validation.__errorsArray.value).map((errorField) => {
					let matched = errorField.match(regex);
					if (matched) {
						if (validation.__errorsArray.value.hasOwnProperty(field)) {
							result = validation.__errorsArray.value[field][0];
						} else if (validation.__errorsArray.value.hasOwnProperty(errorField)) {
							result = validation.__errorsArray.value[errorField][0];
						}
						return false;
					}
				});
				return result;
			} else {
				return validation.__errorsArray.value.hasOwnProperty(field) ? validation.__errorsArray.value[field][0] : null;
			}
		}).value;
	},

	setErrors(errors) {
		validation.__errorsArray.value = errors;
	},

	clearErrors() {
		validation.__errorsArray.value = {};
	},
};
