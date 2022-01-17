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
        var shortenedFirstName  = '';
        var shortenedLastName   = '';
        if (nombres) {
            shortenedFirstName  = this.capitalizeFirstLetter(nombres.split(' ')[0]);
        }
        if (apellidos) {
            shortenedLastName   = this.capitalizeFirstLetter(apellidos.split(' ')[0]);
        }
        const shortenedFullName = shortenedFirstName + ' ' + shortenedLastName;
        return shortenedFullName;
    }
    apaName(arrayUsers){
        console.log(arrayUsers);
        var nameString = '';
        arrayUsers.forEach(user => {
            nameString += user.nombres.charAt(0) + '. ';
            nameString += this.capitalizeFirstLetter(user.apellidos.split(' ')[0]) + '; ';
        });

        return nameString.substring(0, nameString.length-2);
    }
    concatAll(arrayData) {
        let rpta = '';
        arrayData.forEach(data => {
            rpta = rpta + ', '+ data;
        });
        return rpta.slice(2);
    }
    cutName(name){
        let rpta = '';
        name = name.split(' ');
        name.forEach(init => {
            rpta = rpta + init[0].toUpperCase();
        });
        return rpta;
    }
    mergedStates(fid) {
        // estadoInscripcion = [P=pendiente,R=rechazado,A=aprobado,V=verificadoPorDirector]
        // estadoAprobado = [D=enDesarrollo,A=aprobado,R=reprobado]
        // tipoTrabajo = [tesis, memoria, proyectoDeTitulo ]
        const   estadoInscripcion = fid.aprobado_pg;
        const   estadoAprobado = fid.estado;
        const   tipoTrabajo = fid.tipo;
        let     gL = 'a'; // Género Lingüístico
        if (tipoTrabajo == 'Proyecto de titulo') gL = 'o';
        const mergedStates = [
            {eI: 'P', eA: 'D', resultado: tipoTrabajo + ' pendiente de revisión de Prof. Guía.'},
            {eI: 'R', eA: 'D', resultado: tipoTrabajo + ' rechazad'+gL+' provisionalmente, se espera edición estudiantil.'},
            {eI: 'A', eA: 'D', resultado: tipoTrabajo + ' aceptad'+gL+' por profesor Guía, trabajo en ejecución.'},
            {eI: 'V', eA: 'D', resultado: tipoTrabajo + ' aceptad'+gL+' por dirección, trabajo en ejecución.'},
            {eI: 'V', eA: 'A', resultado: tipoTrabajo + ' aprobad'+gL+'.'},
            {eI: 'V', eA: 'R', resultado: tipoTrabajo + ' reprobad'+gL+'.'},
            {eI: 'EA', eA: 'X', resultado: tipoTrabajo + ' en abandono.'},
        ];
        let selectedState = {};
        mergedStates.forEach(state => {
            if ((state.eI == estadoInscripcion) && (state.eA == estadoAprobado || state.eA == 'X')) selectedState= state;
        });
        if (Object.keys(selectedState).length == 0)
            selectedState= {eI: '', eA: '', resultado: tipoTrabajo + ' en estado no determinado (EI: '+estadoInscripcion+', EA: '+estadoAprobado+').'};
        return selectedState;
    };
    listStates(indexBanned) {
        let mergedStates = [
            {id:-1, eI: '',  eA: '',  resultado: 'Todas'},
            {id: 0 ,eI: 'P', eA: 'D', resultado: 'Pendiente de revisión de Prof. Guía'},
            {id: 1 ,eI: 'R', eA: 'D', resultado: 'Rechazado provisionalmente, se espera edición'},
            {id: 2 ,eI: 'A', eA: 'D', resultado: 'Aceptado por profesor Guía, trabajo en ejecución.'},
            {id: 3 ,eI: 'V', eA: 'D', resultado: 'Aceptado por dirección, trabajo en ejecución.'},
            {id: 4 ,eI: 'V', eA: 'A', resultado: 'Aprobado'},
            {id: 5 ,eI: 'V', eA: 'R', resultado: 'Reprobado'},
            {id: 6 ,eI: 'EA', eA: 'X', resultado: 'En abandono'},
        ];
        if (indexBanned) {
            indexBanned.forEach(index => {
                mergedStates = mergedStates.filter(state => state.id !== index);
            });
        }
        return mergedStates;
    }
}
