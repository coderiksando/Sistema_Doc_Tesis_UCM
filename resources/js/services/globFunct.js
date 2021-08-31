export default class globFunct {
    capitalizeFirstLetter(string) {
        string = string.toLowerCase();
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    booleanElements(booleanObject) {
        var keys = Object.keys(booleanObject);
        let aux = true;
        keys.forEach(key => {
            if(booleanObject[key] != true) aux = false;
        });
        return aux;
    }
    cutFullName(nombres, apellidos) {
        const shortenedFirstName  = this.capitalizeFirstLetter(nombres.splite(' '));
        const shortenedLastName   = this.capitalizeFirstLetter(apellidos.splite(' '));
        const shortenedFullName = shortenedFirstName + shortenedLastName;
        return shortenedFullName;
    }
}
