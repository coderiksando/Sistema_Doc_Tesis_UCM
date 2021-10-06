export default class globVar {
    btnClear = 'Limpiar';
    btnSave = 'Guardar';
    btnSearch = 'Buscar';
    btnClose = 'Cerrar';
    btnSend = 'Enviar';
    btnBack = 'Regresar';
    mergedStates = [
        {eI: 'P', eA: 'D', resultado: 'Pendiente de revisión de Prof. Guía.'},
        {eI: 'R', eA: 'D', resultado: 'Rechazado provisionalmente, se espera edición.'},
        {eI: 'A', eA: 'D', resultado: 'Aceptado por profesor Guía.'},
        {eI: 'V', eA: 'D', resultado: 'Aceptado por dirección.'},
        {eI: 'V', eA: 'A', resultado: 'Aprobado.'},
        {eI: 'V', eA: 'R', resultado: 'Reprobado.'},
        {eI: 'R', eA: 'R', resultado: 'En abandono.'},
    ];
}
