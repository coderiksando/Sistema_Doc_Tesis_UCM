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
        const shortenedFirstName  = this.capitalizeFirstLetter(nombres.split(' ')[0]);
        const shortenedLastName   = this.capitalizeFirstLetter(apellidos.split(' ')[0]);
        const shortenedFullName = shortenedFirstName + ' ' + shortenedLastName;
        return shortenedFullName;
    }
    concatAll(arrayData) {
        let rpta = '';
        arrayData.forEach(data => {
            rpta = rpta + ', '+ data;
        });
        return rpta.slice(2);
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
            {eI: 'A', eA: 'D', resultado: tipoTrabajo + ' aceptad'+gL+' por profesor Guía.'},
            {eI: 'V', eA: 'D', resultado: tipoTrabajo + ' aceptad'+gL+' por dirección.'},
            {eI: 'V', eA: 'A', resultado: tipoTrabajo + ' aprobad'+gL+'.'},
            {eI: 'V', eA: 'R', resultado: tipoTrabajo + ' reprobad'+gL+'.'},
            {eI: 'R', eA: 'R', resultado: tipoTrabajo + ' en abandono.'},
        ];
        let selectedState = {};
        mergedStates.forEach(state => {
            if (state.eI == estadoInscripcion && state.eA == estadoAprobado) selectedState= state;
        });
        return selectedState;
    };
    listStates(indexBanned) {
        let mergedStates = [
            {id:-1, eI: '',  eA: '',  resultado: 'Todas'},
            {id: 0 ,eI: 'P', eA: 'D', resultado: 'Pendiente de revisión de Prof. Guía'},
            {id: 1 ,eI: 'R', eA: 'D', resultado: 'Rechazado provisionalmente, se espera edición'},
            {id: 2 ,eI: 'A', eA: 'D', resultado: 'Aceptado por profesor Guía'},
            {id: 3 ,eI: 'V', eA: 'D', resultado: 'Aceptado por dirección'},
            {id: 4 ,eI: 'V', eA: 'A', resultado: 'Aprobado'},
            {id: 5 ,eI: 'V', eA: 'R', resultado: 'Reprobado'},
            {id: 6 ,eI: 'R', eA: 'R', resultado: 'En abandono'},
        ];
        if (indexBanned) {
            indexBanned.forEach(index => {
                mergedStates = mergedStates.filter(state => state.id !== index);
            });
        }
        return mergedStates;
    }
}
