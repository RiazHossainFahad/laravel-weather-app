window.convertToFormData = function (obj, form, namespace) {
    let formDataObj = form || new FormData();
    let formKey;

    for (let property in obj) {
        // if(obj.hasOwnProperty(property) && obj[property]) 
        if (obj.hasOwnProperty(property) && obj[property] !== null && obj[property] !== undefined) {
            if (namespace) {
                formKey = namespace + '[' + property + ']';
            } else {
                formKey = property;
            }

            // if the property is an object, but not a File, use recursivity.
            if (typeof obj[property] === 'object' && !(obj[property] instanceof File)) {
                if (Object.keys(obj[property]).length === 0) { //if it's an empty object then add the key with empty value. 
                    formDataObj.append(formKey, "");
                } else { //else use recursivity
                    convertToFormData(obj[property], formDataObj, formKey);
                }
            } else if (obj[property] instanceof Date) {
                formDataObj.append(formKey, obj[property].toISOString());
            } else { // if it's a string or a File object
                formDataObj.append(formKey, obj[property]);
            }
        }
    }

    return formDataObj;
}