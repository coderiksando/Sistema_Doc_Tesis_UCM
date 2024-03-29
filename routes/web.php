<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



/*********      RUTAS MODULO DE AUTENTICACION , REGISTRO  Y RECUPERACION DE CONTRASENA     *********/
Route::post('/authenticate/login', 'Auth\LoginController@login');
Route::get('/administracion/escuelas/getListarEscuelas', 'Administracion\EscuelasController@getListarEscuelas');
Route::get('/administracion/escuelas/getListarFacultades', 'Administracion\EscuelasController@getListarFacultades');
Route::get('/administracion/reportes/getListarTesisHome', 'Administracion\ReportesController@getListarTesisHome');
Route::get('/administracion/reportes/getListarLogs', 'Administracion\ReportesController@getListarLogs');
Route::get('/reportes/getListarProfesorByEscuela', 'Administracion\ReportesController@getListarProfesorByEscuela');
Route::get('/user/getListarEscuelasByFITsByProfesor', 'Administracion\EscuelasController@getListarEscuelasByFITsByProfesor');
Route::post('/authenticate/registro/setRegistrarAlumno', 'AlumnoController@setRegistrarAlumno');
Route::post('/authenticate/registro/setEditarRolAlumno', 'AlumnoController@setEditarRolAlumno');
Route::post('/passrecovery/sendToken','Auth\LoginController@sendToken');
Route::post('/passrecovery/validateToken','Auth\LoginController@validateToken');
Route::post('/passrecovery/resetPassword','Auth\LoginController@resetPassword');
Route::get('/authenticate/getMyOwnUser', 'Auth\LoginController@getMyOwnUser');
/*********      RUTAS QUE REQUIEREN AUTENTICACION        *********/
Route::group(['middleware' => ['auth']], function () {

  Route::post('/authenticate/logout', 'Auth\LoginController@logout');
  Route::get('/authenticate/getRefrescarUsuarioAutenticado', function () {
    return Auth::user()->load('file');
  });
  /*********      RUTAS MODULO ADMINISTRACION DE USUARIOS           *********/
  Route::get('/administracion/usuario/getListarUsuarios', 'Administracion\UsersController@getListarUsuarios');
  Route::get('/administracion/usuario/getListarUserById', 'Administracion\UsersController@getListarUserById');
  Route::post('/administracion/usuario/setRegistrarUsuario', 'Administracion\UsersController@setRegistrarUsuario');
  Route::post('/administracion/usuario/setEditarUsuario', 'Administracion\UsersController@setEditarUsuario');
  Route::post('/administracion/usuario/setEditarUsuarioView', 'Administracion\UsersController@setEditarUsuarioView');
  Route::post('/administracion/usuario/setEliminarUsuario', 'Administracion\UsersController@setEliminarUsuario');
  Route::post('/administracion/usuario/setEditarDetalleAlumno', 'Administracion\UsersController@setEditarDetalleAlumno');
  Route::post('/administracion/usuario/setCambiarEstadoUsuario', 'Administracion\UsersController@setCambiarEstadoUsuario');
  Route::post('/administracion/usuario/setEditarRolByUsuario', 'Administracion\UsersController@setEditarRolByUsuario');
  Route::get('/administracion/usuario/getRolByUsuario', 'Administracion\UsersController@getRolByUsuario');
  Route::get('/administracion/usuario/getListarPermisosByRolAsignado', 'Administracion\UsersController@getListarPermisosByRolAsignado');
  Route::get('/administracion/usuario/getListarPermisosByUsuario', 'Administracion\UsersController@getListarPermisosByUsuario');
  Route::post('/administracion/usuario/setRegistrarPermisosByUsuario', 'Administracion\UsersController@setRegistrarPermisosByUsuario');
  Route::get('/administracion/usuario/getListarRolPermisosByUsuario', 'Administracion\UsersController@getListarRolPermisosByUsuario');
  Route::post('/administracion/usuario/setImportUsers', 'Administracion\UsersController@importExcel');
  Route::post('/administracion/usuario/calendarData', 'Administracion\UsersController@calendarData');

  /*********    RUTAS MODULO DE ADMINISTRACION DE ROLES      **********/
  Route::get('/administracion/roles/getListarRoles', 'Administracion\RolesController@getListarRoles');
  Route::get('/administracion/roles/getListarPermisosByRol', 'Administracion\RolesController@getListarPermisosByRol');
  Route::get('/administracion/roles/getListarPermisos', 'Administracion\RolesController@getListarPermisos');
  Route::post('/administracion/roles/setRegistrarRolPermisos', 'Administracion\RolesController@setRegistrarRolPermisos');
  Route::post('/administracion/roles/setEditarRolPermisos', 'Administracion\RolesController@setEditarRolPermisos');

  /*********    RUTAS MODULO DE ADMINISTRACION DE PERMISOS     **********/
  Route::get('/administracion/permisos/getListarPermisos', 'Administracion\PermissionController@getListarPermisos');
  Route::post('/administracion/permisos/setRegistrarPermisos', 'Administracion\PermissionController@setRegistrarPermisos');
  Route::post('/administracion/permisos/setEditarPermisos', 'Administracion\PermissionController@setEditarPermisos');

  /*********    RUTAS MODULO DE ADMINISTRACION DE ESCUELAS     **********/
  Route::post('/administracion/escuelas/setRegistrarEscuelas', 'Administracion\EscuelasController@setRegistrarEscuelas');
  Route::post('/administracion/escuelas/setRegistrarFacultad', 'Administracion\EscuelasController@setRegistrarFacultad');
  Route::post('/administracion/escuelas/setEditarEscuelas', 'Administracion\EscuelasController@setEditarEscuelas');
  Route::post('/administracion/escuelas/setRegistrarLinkEscuela', 'Administracion\EscuelasController@setRegistrarLinkEscuela');
  Route::post('/administracion/escuelas/setEditarLinkEscuela', 'Administracion\EscuelasController@setEditarLinkEscuela');
  Route::post('/archivo/setRegistrarArchivoEscuela', 'FilesController@setRegistrarArchivoEscuela');
  Route::post('/archivo/setEditarArchivoEscuela', 'Administracion\EscuelasController@setEditarDocumentoEscuela');
  Route::post('/archivo/setEliminarDocumentoEscuela', 'FilesController@setEliminarDocumentoEscuela');
  Route::get('/administracion/escuelas/getDocumentosEscuela', 'Administracion\EscuelasController@getSeleccionarDocumentoEscuela');
  Route::get('/administracion/escuelas/getListarDocumentosEscuela', 'Administracion\EscuelasController@getListarDocumentosEscuela');
  Route::get('/administracion/escuelas/getEscuela', 'Administracion\EscuelasController@getEscuela');

  /*********    RUTAS MODULO DE ADMINISTRACION DE AREAS DE TESIS     **********/
  Route::get('/administracion/areatesis/getListarAreaTesis', 'Administracion\AreaTesisController@getListarAreaTesis');
  Route::post('/administracion/areatesis/setRegistrarAreaTesis', 'Administracion\AreaTesisController@setRegistrarAreaTesis');
  Route::post('/administracion/areatesis/setEditarAreaTesis', 'Administracion\AreaTesisController@setEditarAreaTesis');

  /*********    RUTAS MODULO DE ADMINISTRACION DE VINCULACIONES     **********/
  Route::get('/administracion/vinculacion/getListarVinculacion', 'Administracion\VinculacionController@getListarVinculacion');
  Route::get('/administracion/vinculacion/getListarTipoVinculacion', 'Administracion\VinculacionController@getListarTipoVinculacion');
  Route::get('/administracion/vinculacion/getListarVinculacionActiva', 'Administracion\VinculacionController@getListarVinculacionActiva');  //creada para  PAF36
  Route::post('/administracion/vinculacion/setRegistrarVinculacion', 'Administracion\VinculacionController@setRegistrarVinculacion');
  Route::post('/administracion/vinculacion/setEditarVinculacion', 'Administracion\VinculacionController@setEditarVinculacion');
  Route::post('/administracion/vinculacion/setCambiarVinculacion', 'Administracion\VinculacionController@setCambiarVinculacion');

  /*********    RUTAS MODULO DE ADMINISTRACION DE TESIS (FIT)    **********/
  Route::get('/alumno/getListarTesis', 'AlumnoController@getListarTesis');
  Route::get('/alumno/getTesisById', 'AlumnoController@getTesisById');
  Route::get('/alumno/getTesisByIdExtendida', 'AlumnoController@getTesisByIdExtendida');
  Route::get('/alumno/getListarAllTesis', 'AlumnoController@getListarAllTesis'); //agrego
  Route::get('/alumno/getListarTesisView', 'AlumnoController@getListarTesisView');
  Route::get('/alumno/getListarTesisTerminadas', 'AlumnoController@getListarTesisTerminadas');
  Route::get('/alumno/getFidEnDesarrollo', 'AlumnoController@getFidEnDesarrollo');
  Route::post('/alumno/setRegistrarTesis', 'AlumnoController@setRegistrarTesis');
  Route::post('/alumno/setCambiarEstadoFIT', 'AlumnoController@setCambiarEstadoFIT');
  Route::post('/alumno/setEditarTesis', 'AlumnoController@setEditarTesis');
  Route::get('/alumno/getListarProfesores', 'AlumnoController@getListarProfesores');
  Route::get('/alumno/getListarMiTesis','AlumnoController@getListarMiTesis');
  Route::get('/alumno/getAllUserRoll','AlumnoController@getAllUserRoll');
  Route::post('/alumno/getUsersAlumnosParametros','AlumnoController@getUsersAlumnosParametros');
  Route::get('/alumno/getRevisionesComision','AlumnoController@getRevisionesComision');
  Route::post('/alumno/setEditarEstadoTesis', 'AlumnoController@setEditarEstadoTesis');

  /*********    RUTAS MODULO DE ADMINISTRACION DE AVANCES DE TESIS   **********/
  Route::get('/avances/getListarAvances', 'AvancesController@getListarAvances');
  Route::get('/avances/getSeleccionarAvance', 'AvancesController@getSeleccionarAvance');
  Route::post('/avances/setRegistrarAvance', 'AvancesController@setRegistrarAvance');
  Route::post('/avances/setEditarAvance', 'AvancesController@setEditarAvance');
  Route::post('/avances/setAvanceARevision', 'AvancesController@setAvanceARevision');
  Route::get('/avances/getListarAvancesByFit', 'AvancesController@getListarAvancesByFit');
  Route::get('/avances/getListarAlumnosByprofesor', 'AvancesController@getListarAlumnosByprofesor');
  Route::get('/avances/getListarFitsByprofesor', 'AvancesController@getListarFitsByprofesor');
  Route::get('/avances/getEstadoTesis', 'AvancesController@getEstadoTesis');
  Route::post('/avances/setRegistrarFinalPdf', 'AvancesController@setRegistrarFinalPdf');

  /*********    RUTAS MODULO DE ADMINISTRACION DE BITACORAS    **********/
  Route::get('bitacoras/getListarMisBitacoras', 'BitacorasController@getListarMisBitacoras');
  Route::post('/bitacoras/setRegistrarBitacora', 'BitacorasController@setRegistrarBitacora');
  Route::get('/bitacoras/getListarBitacorasByAlumno', 'BitacorasController@getListarBitacorasByAlumno');
  Route::get('/bitacoras/getListarBitacorasByFit', 'BitacorasController@getListarBitacorasByFit');
  Route::post('/bitacoras/setEditarBitacora', 'BitacorasController@setEditarBitacora');

  /*********    RUTAS MODULO DE ADMINISTRACION DE NOTAS PENDIENTES    **********/
  Route::get('/notaspendientes/getListarNotasPendientes', 'NotasPendientesController@getListarNotasPendientes');
  Route::get('/notaspendientes/getListarNotasPendientesByEscuela', 'NotasPendientesController@getListarNotasPendientesByEscuela');
  Route::get('/notaspendientes/getListarMiNotaP', 'NotasPendientesController@getListarMiNotaP');
  Route::post('/notaspendientes/setRegistrarNotaP', 'NotasPendientesController@setRegistrarNotaP');;
  Route::post('/notaspendientes/setAsignarNotaP', 'NotasPendientesController@setAsignarNotaP');
  Route::get('/notaspendientes/getMiNotaP', 'NotasPendientesController@getMiNotaP');
  Route::get('/notaspendientes/getNotaPendienteById', 'NotasPendientesController@getNotaPendienteById');
  Route::post('/notaspendientes/setEditarNotaP', 'NotasPendientesController@setEditarNotaP');
  Route::post('/notaspendientes/setIngresarProrroga', 'NotasPendientesController@setIngresarProrroga');
  Route::post('/notaspendientes/asignarNotasP', 'NotasPendientesController@asignarNotasP');
  Route::post('/notaspendientes/eliminarNotaP', 'NotasPendientesController@eliminarNotaP');

  /*********    RUTAS MODULO DE ADMINISTRACION DE COMISIONES    **********/
  Route::post('/comisiones/setRegistrarComision', 'ComisionesController@setRegistrarComision');
  Route::post('/comisiones/setEditarComision', 'ComisionesController@setEditarComision');
  Route::get('/comisiones/getComision', 'ComisionesController@getComision');
  Route::get('/comisiones/getListarMisComisiones', 'ComisionesController@getListarMisComisiones');
  Route::get('/comisiones/getListarComisiones', 'ComisionesController@getListarComisiones');
  Route::get('/comisiones/getListarTodasComisiones', 'ComisionesController@getListarTodasComisiones');
  Route::get('/comisiones/getListarComisionesByParametros', 'ComisionesController@getListarComisionesByParametros');
  Route::get('/comisiones/pathDocumentoComision', 'ComisionesController@pathDocumentoComision');
  Route::post('/comisiones/setRegistrarDocumentoComision', 'ComisionesController@setRegistrarDocumentoComision');
  Route::post('/comisiones/borrarComision', 'ComisionesController@borrarComision');
  Route::post('/comisiones/descargaActaZip', 'FilesController@descargaActaZip');

  /*********    RUTAS MODULO DE ADMINISTRACION DE REPORTES    **********/
  Route::Post('/administracion/tesis/setGenerarDocumento', 'AlumnoController@setGenerarDocumento');
  Route::get('/administracion/reportes/getListarTesisReporte', 'Administracion\ReportesController@getListarTesisReporte');
  Route::post('/administracion/reportes/export', 'Administracion\ReportesController@export');
  Route::post('/archivo/setRegistrarArchivo', 'FilesController@setRegistrarArchivo');
  Route::post('/archivo/setRegistrarArchivoPDF', 'FilesController@setRegistrarArchivoPDF');
  Route::get('/archivo/getArchivo', 'FilesController@getArchivo');
  Route::post('/archivo/setRegistrarTesisfinalizada', 'FilesController@setRegistrarTesisfinalizada');
  Route::post('/archivo/setEditarTesisfinalizada', 'FilesController@setEditarTesisfinalizada');
  Route::post('/archivo/descargaZipByFid', 'FilesController@descargaZipByFid');
  /*********    RUTAS MODULO DE ADMINISTRACION DE DOCUMENTOS ALUMNOS     **********/
  Route::get('/secretaria/getListarAlumnos', 'SecretariaController@getListarAlumnos');
  Route::get('/secretaria/getListarActas', 'SecretariaController@getListarActas');
  Route::Post('/secretaria/setGenerarMemoRevision', 'SecretariaController@setGenerarMemoRevision');
  Route::post('/secretaria/setSubirActa', 'SecretariaController@setSubirActa');
  Route::post('/secretaria/setRegistrarNota', 'SecretariaController@setRegistrarNota');
  Route::post('/secretaria/setRegistroAlumnoDara', 'SecretariaController@setregistroAlumnoDara');
  Route::post('/secretaria/setRegistrarDefensaActa', 'SecretariaController@setRegistrarDefensaActa');

  /*********    RUTAS MODULO DE SELECCION DE ROL   **********/
  Route::post('/perfil/setRol', 'Auth\LoginController@changeRol');

  /*********    RUTAS MODULO DE ADMINISTRACION DE PARAMETROS     **********/
  Route::post('/admin/setParametros', 'ParametrosController@setParametros');
});//cierre de rutas que requieren autenticacion
Route::post('/admin/parametros', 'ParametrosController@getParametros');


/********** RUTA BASE **********/
Route::get('/{optional?}', function () {
  return view('app');
})->name('basepath')
->where('optional', '.*');
