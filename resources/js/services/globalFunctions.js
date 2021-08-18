export default class globalFunctions {

    capitalizeFirstLetter(string) {
        string = string.toLowerCase();
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    booleanElements(booleanObject) {
        var keys = Object.keys(booleanObject);
        let aux = true;
        keys.forEach(key => {
            if(booleanObject[key] != true) {
                aux = false;
            }
        });
        return aux;
    }

}
