<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">Bienvenido</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div class="container container-fluid">
      <div class="card">
        <div class="card-body px-0">
          <div class="container-fluid">
            <div class="card-body px-0">
                <div class="row">
                  <div class="col-md-12">
                    <div id="accordion">

                      <div class="card-white" v-if="listRolPermisosByUsuario.includes('usuarios.ver')" ref="usuarios">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#usuarios' aria-expanded="false" aria-controls='usuarios'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#usuarios' aria-expanded="false" aria-controls='usuarios'>
                              Usuarios
                            </button>
                          </h5>
                        </div>
                        <div id='usuarios' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('usuarios.crear')">
                                <dt ref="usuarioscrear">Creación de usuario</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para la creación de un usuario, seleccione el botón de la esquina superior derecha, titulado como "Nuevo Usuario",
                                    se abrirá una interfaz de captación de datos, los cuales serán los datos del usuario que pretende crear.
                                    Entre los campos a receptar, estarán nombres y apellidos que conformará el nombre completo del usuario,
                                    correo el cual suscribirá el usuario (éste no podrá ser editado posteriormente), asignación de escuela a la que pertenece,
                                    contraseña verificada, el rut (no validado) y el rol del usuario, donde los roles pueden ser múltiples o únicos.
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('usuarios.importar')">
                                <dt ref="usuariosimportar">Importación de usuarios mediante plantillas</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    La importación de usuarios es un método rápido de inserción de usuarios, mediante la inserción de un archivo excel
                                    con un orden específico entregado mediante la interfaz, donde el archivo debe poseer cabeceras y estar ordenados en el siguienmte orden:
                                    (Email, Fono, Rut, Nombre, Apellido). Para la inserción de un archivo haga click en "Seleccionar archivo",
                                    luego de agregado el archivo seleccione la escuela a cual pertenece este documento, con esto listo, puede dar click en el botón "Guardar".
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('usuarios.editar')">
                                <dt ref="usuarioseditar">Edición de usuarios</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para poder editar un usuario creado en la plataforma, debe realizar una búsqueda del usuario mediante los criterios
                                    de búsqueda, o bien, buscar al usuario a través de la lista entregada en "Bandeja de resultados". Luego de encontrar
                                    al usuario que desea editar, seleccione el botón "Ver y Editar" con el ícono representativo <i class="fas fa-eye"></i>.
                                    En la interfaz abierta, edite los campos que desee cambiar y para finalizar haga click en el botón "Guardar"
                                    situado en la parte inferior de la pantalla.
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('usuarios.permisos')">
                                <dt ref="usuariospermisos">Agregar permisos a usuarios</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Cada usuario tiene definidos según el rol los permisos en la plataforma, pero puede darse el caso que un usuario necesite más permisos no definidos por su rol,
                                    para otorgar éstos, debe realizar una búsqueda del usuario mediante los criterios de búsqueda, o bien,
                                    buscar al usuario a través de la lista entregada en "Bandeja de resultados". Luego de encontrar al usuario,
                                    seleccione el botón "Permisos" con el ícono representativo <i class="fas fa-key"></i>. En la interfaz abierta seleccione los permisos que desee entregar al usuario,
                                    para luego hacer click en el botón "Guardar" en la sección inferior de la pantalla.
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('usuarios.index')">
                                <dt ref="usuariosindex">Desactivar usuario</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Si requiere que un usuario no sea encontrado en los reportes o quitado de las opciones de selección de {{terminoTitulo}}s,
                                    busque al usuario que desee desactivar, a través de la sección criterios de búsqueda y luego haga click en el botón "Desactivar usuario"
                                    con botón representativo <i class="fas fa-ban"></i>. Esta medida impedirá que el usuario pueda ingresar al sistema, pero sus registros se mantendrán en la plataforma.
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('usuarios.index')">
                                <dt>Eliminar usuario</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    La eliminación de un usuario es la última instancia de quitar registros de alguna cuenta en la plataforma, esta acción eliminará de forma permanente
                                    al usuario en cuestión y solo se podrá realizar con usuarios que no tengan ningún tipo de registros en la plataforma además de su propia cuenta,
                                    pues el sistema tiene como misión mantener cada dato intacto. Para realizar una eliminación de usuario busque al usuario en cuestión mediante los criterios de búsqueda,
                                    una vez encontrado haga click en el botón "Eliminar usuario" con ícono representativo <i class="fas fa-trash-alt"></i>.
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('usuarios.ver')">
                                <dt ref="usuariosver">Edición de mi perfil</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para realizar cambios en el perfil de nuestras cuentas, nos dirigimos al navbar sección que está en la esquina superior derecha,
                                    damos click en el botón de ícono representativo <i class="fa fa-user-circle"></i>, para luego seleccionar nuestro nombre en la ventana emergente.
                                    Se abrirá una interfaz donde precargará nuestros datos y podremos realizar cambios en éstos, luego de realizar los cambios haga click en el botón "Guardar".
                                </dd>
                            </dl>
                        </div>
                      </div>

                      <div class="card-white" v-if="listRolPermisosByUsuario.includes('roles.index')" ref="roles">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#roles' aria-expanded="false" aria-controls='roles'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#roles' aria-expanded="false" aria-controls='roles'>
                              Roles
                            </button>
                          </h5>
                        </div>
                        <div id='roles' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('roles.crear')">
                                <dt ref="rolescrear">Crear roles</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para la creación de un nuevo rol, debemos acceder al botón titulado "Nuevo Rol", situado en la esquina superior derecha
                                    de la pantalla con ícono representativo <i class="fas fa-plus-square"></i>, una vez en la nueva interfaz titulada "Crear rol", se espera una inserción del nombre del rol y
                                    la inserción de los permisos asociados a ese rol. Una vez ingresados todos los datos, haga click en el botón "Guardar".
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('roles.editar')">
                                <dt ref="roleseditar">Editar roles</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Si requiere editar un rol dentro del sistema, donde pueda cambiar los roles o el mismo nombre del rol,
                                    debe encontrar al rol a editar, una vez localizado haga click en el botón "Editar" con ícono representativo
                                    <i title="Editar" class="fas fa-pencil-alt"></i>. Ya en la interfaz llamada "Editar rol", puede renombrar y
                                    seleccionar los permisos asociados al rol, si requiere de algún rol en específico,
                                    puede utilizar el buscador de permisos en la sección llamada "Listar permisos". Una vez terminada la edición, haga click en el botón "Guardar"
                                    situado en la zona de "Formulario de edición de rol" o en la zona inferior central de la interfaz.
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('roles.index')">
                                <dt ref="rolesindex">Ver información de rol</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Si necesita tan solo revisar la información del permiso, acceda al botón titulado "Ver" con ícono representativo <i title="Ver" class="fas fa-eye"></i>.
                                    Luego de hacer click, se abrirá un modal que presentará el nombre, slug y permisos asociados al rol.
                                </dd>
                            </dl>
                        </div>
                      </div>

                      <div class="card-white" v-if="listRolPermisosByUsuario.includes('escuelas.index')">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#escuelas' aria-expanded="false" aria-controls='escuelas'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#escuelas' aria-expanded="false" aria-controls='escuelas'>
                              Escuelas
                            </button>
                          </h5>
                        </div>
                        <div id='escuelas' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('escuelas.crearfacultad')">
                                <dt ref="escuelascrearfacultad">Creación de facultad</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para la creación de una nueva facultad en la plataforma, debe acceder al botón que está en la sección superior
                                    derecha titulado "Nueva Facultad" con el ícono representativo <i class="fas fa-plus-square"></i>. Luego de ingresar
                                    a la interfaz llamada "Crear nueva facultad", se pedirá la información del nombre de la facultad (Se espera un nombre completo).
                                    Luego de tener todos los datos haga click en el botón "Guardar".
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('escuelas.crear')">
                                <dt ref="escuelascrear">Creación de escuela</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para la creación de una nueva escuela en la plataforma, debe acceder al botón que está en la sección superior
                                    derecha titulado "Nueva Escuela" con el ícono representativo <i class="fas fa-plus-square"></i>. Luego de ingresar
                                    a la interfaz llamada "Crear nueva escuela", se pedirá la información en cada uno de los inputs tales como "Nombre",
                                    "Abreviación" (es el nombre o las iniciales de una escuela, ejemplo: ICI para Ingeniería civil informática) y la facultad a la cual pertenece.
                                    Luego de tener todos los datos haga click en el botón "Guardar".
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('escuelas.editar')">
                                <dt ref="escuelaseditar">Edición de escuela</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para realizar una edición a una escuela ya creada en la plataforma, debe buscar la escuela que desea
                                    editar en la sección "bandeja de resultados" (tambien puede ayudar la sección de búsqueda en "Criterios de búsqueda")
                                    y hacer click en el botón "Editar" con el ícono representativo <i class="fas fa-pencil-alt"></i>, dispuesto en la columna "acciones".
                                    Ya en la interfaz llamada "Editar escuela", se precargarán los datos de la escuela a editar, puede reemplazar cada dato a disposición,
                                    sin embargo no podrá realizar una edición de una escuela llamándola igual a otra anteriormente creada.
                                </dd>
                            </dl>
                        </div>
                      </div>

                      <div class="card-white" v-if="listRolPermisosByUsuario.includes('escuelas.documentos.crear')" ref="escuelasdocumentos">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#areatesis' aria-expanded="false" aria-controls='areatesis'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#areatesis' aria-expanded="false" aria-controls='areatesis'>
                              Documentos de escuela
                            </button>
                          </h5>
                        </div>
                        <div id='areatesis' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('escuelas.documentos.crear')">
                                <dt ref="escuelasdocumentoscrear">Crear información de escuela</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para entregar información a disposición de los usuarios, utilice la interfaz "Información de escuela", en ella podrá acceder a
                                    la sección "Ingresar información", donde esperará un nombre,
                                    <template v-if="listRolPermisosByUsuario.includes('escuelas.documentos.general')">
                                    la escuela a la que pertenece (puede hacer una selección de "General", para que este documento se presente a todos),
                                    </template>
                                    tipo (en esta sección podrá hacer ingreso de un documento o un link, todo dependerá del tipo que ha elegido),
                                    Archivo (donde podrá ingresar un documento descargable para los usuarios) o URL (donde su dirección será automáticamente
                                    registrada con el formato necesario ejemplo: 'www.youtube.com' será registrado como 'https://www.youtube.com').
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('escuelas.documentos.editar') || listRolPermisosByUsuario.includes('escuelas.documentos.general')">
                                <dt ref="escuelasdocumentoseditar">Edición de información de escuela</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para realizar una edición en un documento o url registrado anteriormente en la plataforma, busque en la sección de "Información de escuela"
                                    el nombre de la información a editar y luego seleccione el botón "Editar documento" con el ícono representativo <i class="fas fa-pencil-alt"></i>.
                                    Ya en la interfaz "Editar Información de Escuela", se hará una precarga de los datos, edite e ingrese los documentos
                                    o url requeridos y haga click en el botón "Guardar".
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('escuelas.documentos.editar') || listRolPermisosByUsuario.includes('escuelas.documentos.general')">
                                <dt ref="escuelasdocumentoseditar2">Eliminar información de escuela</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para eliminar un documento o url registrado anteriormente en la plataforma, busque en la sección de "Información de escuela"
                                    el nombre de la información a editar y luego seleccione el botón "Eliminar documento" con el ícono representativo <i class="fas fa-trash-alt"></i>.
                                    Se abrirá una ventana emergente que pedirá confirmación de la acción, para llevar a cabo esta acción presione el botón "Si, eliminar".
                                </dd>
                            </dl>
                        </div>
                      </div>

                      <div class="card-white" v-if="false && listRolPermisosByUsuario.includes('areatesis.index')" ref="areatesis">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#areatesis' aria-expanded="false" aria-controls='areatesis'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#areatesis' aria-expanded="false" aria-controls='areatesis'>
                              Area de tesis
                            </button>
                          </h5>
                        </div>
                        <div id='areatesis' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('areatesis.crear') || listRolPermisosByUsuario.includes('escuelas.documentos.general')">
                                <dt ref="areatesis.crear">Eliminar información de escuela</dt>
                                <dd class="pl-4 mr-2 text-justify">

                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('areatesis.editar') || listRolPermisosByUsuario.includes('escuelas.documentos.general')">
                                <dt ref="areatesis.editar">Eliminar información de escuela</dt>
                                <dd class="pl-4 mr-2 text-justify">

                                </dd>
                            </dl>
                            <!-- areatesis.index
                            areatesis.crear
                            areatesis.editar -->
                        </div>
                      </div>

                      <div class="card-white" v-if="listRolPermisosByUsuario.includes('vinculacion.crear')" ref="vinculacion">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#vinculacion' aria-expanded="false" aria-controls='vinculacion'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#vinculacion' aria-expanded="false" aria-controls='vinculacion'>
                              Vinculaciones
                            </button>
                          </h5>
                        </div>
                        <div id='vinculacion' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('vinculacion.crear') || listRolPermisosByUsuario.includes('escuelas.documentos.general')">
                                <dt ref="vinculacioncrear">Crear vinculación</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para la creación de una nueva vinculación en la plataforma, debe acceder al botón que está en la sección superior
                                    derecha titulado "Nueva vinculación" con el ícono representativo <i class="fas fa-plus-square"></i>. Luego de ingresar
                                    a la interfaz llamada "Crear vinculación", se pedirá la información en cada uno de los inputs tales como "Nombre",
                                    "Tipos" (que se refiere a la categorización de la vinculación) y Descripción (en este input puede entregar mayor información de la vinculación).
                                    Luego de ingresar todos los datos, haga click en el botón "Guardar".
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('vinculacion.editar') || listRolPermisosByUsuario.includes('escuelas.documentos.general')">
                                <dt ref="vinculacioneditar">Editar vinculación</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para realizar una edición en una vinculación registrada anteriormente en la plataforma, busque en la sección de "Bandeja de resultados"
                                    el nombre de la vinculación a editar y luego seleccione el botón "Editar" con el ícono representativo <i class="fas fa-pencil-alt"></i>.
                                    Ya en la interfaz "Editar vinculación", se hará una precarga de los datos, edite  los campos requeridos y
                                    haga click en el botón "Guardar".
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('vinculacion.editar') || listRolPermisosByUsuario.includes('escuelas.documentos.general')">
                                <dt ref="vinculaciondesactivar">Desactivar vinculación</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para desactivar una vinculación registrada anteriormente en la plataforma, busque en la sección de "Bandeja de resultados"
                                    el nombre de la vinculación a desactivar y luego seleccione el botón "Desactivar" con el ícono representativo <i class="fas fa-ban"></i>.
                                    Al seleccionar el botón, aparecerá una ventana emergente para desactivar la vinculación, si quiere seguir con esta acción haga click
                                    en el botón "Si, desactivar".
                                </dd>
                            </dl>
                        </div>
                      </div>

                      <div class="card-white" v-if="listRolPermisosByUsuario.includes('tesis.fit')" ref="tesis">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#tesis' aria-expanded="false" aria-controls='tesis'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#tesis' aria-expanded="false" aria-controls='tesis'>
                                <template v-if="listRolPermisosByUsuario.includes('EsAlumno')">
                                Inscribir/</template>Revisar {{terminoTitulo}}
                            </button>
                          </h5>
                        </div>
                        <div id='tesis' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('tesis.crear')">
                                <dt ref="tesiscrear">Crear {{terminoTitulo}}</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para la creación de un nuevo {{terminoTitulo}}, debemos acceder al botón titulado como
                                    "Ingresar formulario de inscripción" con el ícono representativo <i class="fas fa-plus-square"></i>.
                                    Ya en la interfaz titulado como "Crear {{terminoTitulo}}", debe ingresar los campos de información e ingresar
                                    a los integrantes de su {{terminoTitulo}}, mediante el botón de <i class="fas fa-user-plus"></i> situado en la
                                    parte inferior izquierda. Luego de hacer click en el botón se abrirá un modal para buscar a los usuarios que
                                    integrarán el {{terminoTitulo}}, haga click en el botón con ícono representativo <i class="fas fa-plus-square"></i> para incorporarlo.
                                    Para terminar la inscripción haga click en el botón "Guardar".
                                    <br>
                                    Durante los próximos días su {{terminoTitulo}} estará a disposición del profesor guía el cual fue seleccionado, y él podrá aceptar,
                                    rechazar o editar su inscripción de {{terminoTitulo}}.
                                    Si su inscripción es rechazada, puede revisar el motivo que ha entregado el profesor guía en la interfaz
                                    "Ingresar/Revisar {{terminoTitulo}}" mediante el botón "Ver razon de rechazo" con el ícono representativo <i class="fas fa-exclamation-circle"></i>,
                                    para luego editar su inscripción y enviar nuevamente al profesor guía en búsqueda de aprobación.
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('tesis.aprobar')">
                                <dt ref="tesisaprobar">Aprobar/Rechazar {{terminoTitulo}}</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para realizar la aceptación o rechazo de un {{terminoTitulo}}, debe encontrar al registro en cuestión,
                                    podemos encontrar estos registros mediante la sección "Criterios de búsqueda", luego de localizar el {{terminoTitulo}}, en la fila del registro
                                    existen dos posibilidades para aprobar/rechazar un registro, mediante la revisión del {{terminoTitulo}}
                                    que puede ser accedido por el botón "Ver {{terminoTitulo}}" con ícono representativo <i class="far fa-eye fa-lg"></i>.
                                    Al ingresar a la interfaz "{{terminoTituloExtendido}} ({{terminoTitulo}})", en la sección inferior de esta interfaz
                                    existen dos botones titulados "Aceptar" y "Rechazar" que referencian a aceptar o rechazar un {{terminoTitulo}},
                                    según la opción que elija encontrará una ventana emergente para corroborar su decisión (en caso que rechace el {{terminoTitulo}},
                                    se espera un comentario para orientar al alumno y realizar ediciones en el {{terminoTitulo}}). Como segundo caso, para aprobar o rechazar un registro,
                                    en la interfaz "Revisar FID" al lado del registro encontrado existen dos botones con íconos representativos <i class="far fa-thumbs-up"></i> y <i class="far fa-thumbs-down"></i>
                                    que puede aprobar o rechazar un {{terminoTitulo}} respectivamente, ambos funcionan mediante una ventana emergente para corroborar sus selecciones.
                                    <br>
                                    Al aceptar un {{terminoTitulo}} por primera vez deberá crear una comisión provisoria, este es el primer paso para conformar una comisión evaluadora,
                                    la cual hará correcciones en el documento de revisión una vez sea enviado.
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('tesis.editar')">
                                <dt ref="tesiseditar">Editar un {{terminoTitulo}}</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    <template v-if="listRolPermisosByUsuario.includes('EsAlumno')">
                                      En caso que un {{terminoTitulo}} haya sido rechazado tendrá la posibilidad de editar este registro,
                                      apegandose a las acotaciones que ha entregado el profesor guía para la búsqueda de aprobación o bien, buscar un nuevo
                                      profesor guía que acepte estos términos.
                                    </template>
                                    Para realizar una edición del {{terminoTitulo}} busque su registro en la sección "Bandeja de resultados", en el lado derecho tendrá la columna de
                                    acciones en donde estará el botón "Editar {{terminoTitulo}}" con ícono representativo <i class="fas fa-pencil-alt"></i>. Al hacer click en el botón se
                                    abrirá una nueva interfaz llamada "Editar {{terminoTitulo}}", donde podrá realizar los cambios que estime conveniente.
                                    Para terminar su edición dirijase a la sección inferior donde encontrará el botón "Guardar", éste dará termino a la edición.
                                    <template v-if="listRolPermisosByUsuario.includes('EsAlumno')">
                                      Luego de realizada esta última acción, el {{terminoTitulo}} estará nuevamente disponible para revisión del profesor guía,
                                      donde podrá aceptar o rechazar la solicitud.
                                    </template>
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="false && listRolPermisosByUsuario.includes('tesis.subirconstancia')">
                                <dt ref="archivoscomisiones">Ingresar constancia de examen</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    <i class="fas fa-file-upload"></i>
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('EsAlumno')">
                                <dt ref="archivoscomisiones">Revisión de comisiones</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Una vez el profesor guía envíe su documento a revisión y la comisión evaluadora haya sido conformada definitivamente,
                                    la comisión evaluadora entregará correcciones del documento donde usted podrá acceder a éstas mediante el botón
                                    "Ver revisiones de comisión" con ícono representativo <i class="fa fa-list-alt"></i>. Luego de hacer click en el botón,
                                    se abrirá una interfaz llamada "Revisiones de la comisión" donde estarán aquellas correcciones entregadas por cada profesor,
                                    donde podrán ser descargadas.
                                </dd>
                            </dl>
                        </div>
                      </div>

                      <div class="card-white" v-if="listRolPermisosByUsuario.includes('reportes.reportefit')" ref="reportes">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#reportes' aria-expanded="false" aria-controls='reportes'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#reportes' aria-expanded="false" aria-controls='reportes'>
                              Reportes de documentos
                            </button>
                          </h5>
                        </div>
                        <div id='reportes' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4">
                                <dt ref="reportesreportefit">Criterios de búsqueda</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para realizar búsqueda de {{terminoTitulo}}, se usarán distintos filtros que estarán situados en la sección
                                    "Criterios de búsqueda", estos servirán para realizar acotaciones de las búsquedas de {{terminoTitulo}}.
                                    <br>
                                    <template v-if="listRolPermisosByUsuario.includes('reportes.general')">
                                       <b>Facultad: </b>Entregará todas las facultades inscritas en la plataforma, al seleccionar alguna de estas la
                                       sección "Escuela" también será acotada, para entregar solo escuelas inscritas en la facultad seleccionada
                                       (estará preseleccionada la Facultad de Ciencias de la Ingeniería), para la búsqueda de todas las facultades haremos click en
                                       la sección derecha del input, donde se mostrará un ícono representativo <i class="el-icon-circle-close"></i>,
                                       para quitar la opción preseleccionada y dejar en blanco este input.
                                       <br>
                                       <b>Escuelas: </b>Entregará todas las escuelas inscritas en el sistema, al seleccionar una escuela se
                                       reducirá la lista de profesores dada en la sección "Profesor", en función de la escuela de pertenencia del profesor.
                                       <br>
                                    </template>
                                    <b>Título: </b>Para realizar una búsqueda a través del título puede escribir el fragmento del título que desea
                                    buscar (esta opción entrega preferentemente un resultado dependiendo del nivel de ambigüedad).
                                    <br>
                                    <b>Rut alumno:</b>Para realizar una búsqueda a través del rut de un alumno inscrito en la plataforma puede escribir
                                    el fragmento o el rut completo del alumno (ejemplo: 11111111-1), buscar (esta opción entrega preferentemente un resultado dependiendo del nivel de ambigüedad).
                                    <br>
                                    <b>Profesor: </b>Entrega una lista de profesores, de los cuales puede seleccionar uno, con esto mostrará todos
                                    los {{terminoTitulo}} que estén asociados a él como profesor guía.
                                    <br>
                                    <b>Avances: </b>Para realizar una búsqueda a través de la cantidad de avances que posee un {{terminoTitulo}}, accederemos a la sección
                                    "Avances" donde activaremos el cuadro en el lado izquierdo de los inputs pertenecientes a la sección "Avances".
                                    Usaremos cada input de la sección como el rango de número de avances que posee el {{terminoTitulo}}.
                                    <br>
                                    <b>Estado de notas: </b>Esta sección entregará los {{terminoTitulo}}, con notas pendientes activas, vencidas, sin notas pendientes y todas, de esta manera
                                    podrá encontrar {{terminoTitulo}} que estén con registros de notas pendientes.
                                    <br>
                                    <b>Estado: </b>Esta sección entregará todos los posibles estados {{terminoTitulo}}, entre ellos estarán los usuales estados,
                                    pero además se entrega una segunda opción dando <b>doble click en la sección "Estado"</b> y se activarán nuevos estados generales para los {{terminoTitulo}}.
                                    Los nuevos estados que se agregarán al filtro de búsqueda son "Estado de inscripción" y "Estado de aprobación". El estado de inscripción
                                    muestra el proceso de aceptación del {{terminoTitulo}} según profesores y directivos. El estado de aprobación hace referencia al estado
                                    en que se encuentra su evaluación luego de ser registrada una nota por su defensa y trabajo.
                                    <br>
                                    <b>Categoría: </b>Entrega una lista de las generalidades de las vinculaciones.
                                    <br>
                                    <b>Vinculación: </b>Esta sección entrega una lista de todas las vinculaciones creadas en la plataforma, según nombre específico.
                                    <br>
                                    <b>Fecha ingreso alumno: </b>Entrega dos campos de información suministrables, donde representan un rango de fechas para
                                    buscar el ingreso del alumno a la carrera cursada.
                                    <br>
                                    <b>Fecha de inscripción: </b>Entrega dos campos de información suministrables, donde representan un rango de fechas para
                                    buscar el ingreso del {{terminoTitulo}} a la plataforma.
                                </dd>
                            </dl>
                            <dl class="pl-4">
                                <dt ref="reportesexportar">Exportar datos</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                  Para extraer datos de los {{terminoTitulo}} registrados en la plataforma, nos dirigiremos a la sección "Bandeja de resultados",
                                  donde en la sección derecha estará a disposición un botón titulado "Exportar" con ícono representativo <i class="fas fa-file-excel"></i>, éste generará
                                  un documento en excel de todos los registros encontrados mediante el filtro hecho en la sección "Criterios de búsqueda".
                                </dd>
                            </dl>
                            <dl class="pl-4">
                                <dt ref="reportesinformacion">Información de {{terminoTitulo}}</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                  En la sección de "Bandeja de resultados", se presentarán todos los {{terminoTitulo}}s, donde podrá hacer click en el
                                  título del registro y aparecerá información complementaria y extendida, además de portar botones de acción para la búsqueda
                                  (visión de: avances, actas de reunión y todos los datos del {{terminoTitulo}}), además otros botones de descarga de documentos
                                  (Documento final, acta de defensa y constancia de examen).
                                </dd>
                            </dl>
                        </div>
                      </div>

                      <div class="card-white" v-if="listRolPermisosByUsuario.includes('avances.index')" ref="avances">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#avances' aria-expanded="false" aria-controls='avances'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#avances' aria-expanded="false" aria-controls='avances'>
                              Avances de documento
                            </button>
                          </h5>
                        </div>
                        <div id='avances' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                          <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('EsAlumno')">
                            <dt ref="archivoscomisiones">Subir Avance</dt>
                            <dd class="pl-4 mr-2 text-justify">
                                Para ingresar un nuevo avance de documento diríjase al botón "Subir avance" con el icono representativo
                                <i class="fas fa-plus-square"></i>. Luego de hacer click en el botón, se abrirá un formulario de registro de avances, en el cual
                                deberá cargar un archivo y darle una breve descripción, el avance se subirá una vez que haga click en el botón guardar.
                            </dd>
                            <dt ref="archivoscomisiones">Editar Avance</dt>
                            <dd class="pl-4 mr-2 text-justify">
                                Para editar un avance de documento diríjase al botón "Editar" con el icono representativo <i class="fas fa-pen"></i> junto al
                                avance que desea editar. Luego de hacer click en el botón, se abrirá un formulario de edición de avances, en el cual puede
                                cargar un nuevo archivo para reemplazar al anterior o darle una nueva descripción al archivo subido anteriormente, el avance se
                                editará una vez que haga click en el botón guardar.
                            </dd>
                            <dt ref="archivoscomisiones">Subir Documento final</dt>
                            <dd class="pl-4 mr-2 text-justify">
                                Una vez que su trabajo de conclusión de curso sea evaluado y aprobado se habilitará un botón en la esquina superior derecha
                                de la sección de avances de color verde, con el icono representativo <i class="fas fa-plus-square"></i> y el texto
                                "Subir PDF final". Luego de hacer click en el botón, se abrirá un formulario de ingreso de documento final, en el cual puede
                                cargar la versión final del documento que se encontrará disponible en el repositorio de la universidad. En la sección inferior
                                de la interfaz se encuentra el Formulario de Autorización de Publicación en el cual se le consultará si desea que el documento
                                subido se encuentre disponible de forma pública. El documento final será ingresado una vez que haga click en el botón guardar.
                            </dd>
                          </dl>
                          <dl class="pl-4" v-else>
                            <dt ref="archivoscomisiones">Selección de alumno</dt>
                            <dd class="pl-4 mr-2 text-justify">
                                Para realizar la búsqueda del conjunto de tesistas, dirijase a la sección de "Criterios de búsqueda", donde señalará el estado
                                actual del grupo (para acotar la búsqueda) y seleccionará al conjunto de alumnos búscado.
                            </dd>
                            <dt ref="archivoscomisiones">Ver avance</dt>
                            <dd class="pl-4 mr-2 text-justify">
                                Si requiere ver el documento vinculado al avance del conjunto de usuarios, diríjase a la sección de "Bandeja de resultados" y
                                seleccione el botón con ícono representativo <i class="fas fa-file-download"> </i> para descargar el documento.
                            </dd>
                            <template v-if="listRolPermisosByUsuario.includes('EsProfesor')">
                                <dt ref="archivoscomisiones">Enviar/Retirar revisión de documento</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Para <b>enviar</b> el documento de avance a revisión en el sistema, debe dirijirse a la sección de "Bandeja de resultados" y
                                    seleccione el botón con ícono representativo <i class="fas fa-file-export"></i> para descargar el documento.
                                    El sistema realizará una comprobación del estado de la comisión, si esta comisión es de caracter provisorio o definitivo,
                                    en caso de ser una comisión provisoria, abrirá un recuadro para la inserción de una comisión definitiva.
                                    <br>
                                    En caso de <b>retirar</b> el documento de avance de revisión, seleccione el botón con ícono representativo <i class="fas fa-file-import"></i>.
                                </dd>
                            </template>
                          </dl>
                            <!--
                                avances.index
                                avances.crear
                                avances.editar
                                avances.listaralumnos
                                avances.subirfinalpdf
                            -->
                        </div>
                      </div>

                      <div class="card-white" v-if="listRolPermisosByUsuario.includes('notaspendientes.index')" ref="notaspendientes">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#notaspendientes' aria-expanded="false" aria-controls='notaspendientes'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#notaspendientes' aria-expanded="false" aria-controls='notaspendientes'>
                              Nota pendiente
                            </button>
                          </h5>
                        </div>
                        <div id='notaspendientes' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4" v-if="!listRolPermisosByUsuario.includes('EsAlumno')">
                                <dt ref="notaspendientesindex">Criterios de búsqueda</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    <b>Alumno: </b>Buscará a cualquier alumno que coincida entre el grupo de {{terminoTitulo}} registrado, esto se refiere
                                    a búsquedas de texto parciales, ya sea en el nombre o apellido del estudiante.
                                    <br>
                                    <b>Estado: </b>Se filtrará por el estado actual de la nota pendiente, buscando solo {{terminoTitulo}}s con notas pendientes
                                    con o sin prórroga.
                                    <br>
                                    <b>Rango de fechas: </b>Entrega dos campos de información que serán usados para la búsqueda del registro de una nota pendiente.
                                </dd>
                                <dt ref="notaspendientesindex">Bandeja de resultados</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    Entregará la información relacionada al {{terminoTitulo}} buscado por los criterios de búsqueda y presentará
                                    los campos propuestos por el o los alumnos del {{terminoTitulo}}.
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('notaspendientes.crear')">
                                <dt ref="notaspendientescrear">Solicitar nota pendiente</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                  Para solicitar una nota pendiente debemos hacer click en el botón "Solicitar nota pendiente" con ícono representativo
                                  <i class="fas fa-plus-square"></i>. Una vez en la interfaz "Ingresar nota pendiente", mostrará una ventana emergente que especifica
                                  que el formulario pedido solo dejará constancia a nivel de facultad, por lo que aun es necesario realizar la solicitud a traves del portal del alumno.
                                  Se pedirá una fecha propuesta para establecer un tiempo máximo para terminar su trabajo de conclusión de curso, al terminar haga click en el
                                  botón "Guardar".
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('notaspendientes.editar')">
                                <dt ref="notaspendienteseditar">Editar solicitud de nota pendiente</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    En la sección "Bandeja de resultados", se dispone el registro de la nota pendiente anteriormente pedida, a su derecha
                                    estará la columna de "acciones", la cual dispone del botón "Editar" con ícono representativo <i class="fas fa-pencil-alt"></i>.
                                    Luego de hacer click, se mostrará la interfaz "Editar nota pendiente", donde se puede editar la fecha de nota pendiente en la
                                    sección "Fecha propuesta", luego de realizar los cambios usaremos el botón "Guardar".
                                </dd>
                            </dl>
                            <dl class="pl-4" v-if="listRolPermisosByUsuario.includes('notaspendientes.prorroga')">
                                <dt ref="notaspendientesprorroga">Solicitar prórroga de nota pendiente</dt>
                                <dd class="pl-4 mr-2 text-justify">
                                    En la sección "Bandeja de resultados", se dispone el registro de la nota pendiente anteriormente pedida, a su derecha
                                    estará la columna de "acciones", la cual dispone del botón "Solicitar prórroga" con ícono representativo <i class="fas fa-calendar-check"></i>.
                                    Luego de hacer click, se mostrará la interfaz "Ingresar prórroga de nota pendiente", donde se puede ingresar la fecha de
                                    prórroga nota pendiente en la sección "Fecha de prórroga", para finalizar usaremos el botón "Guardar".
                                </dd>
                            </dl>
                        </div>
                      </div>

                      <div class="card-white" v-if="listRolPermisosByUsuario.includes('bitacoras.index')">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#bitacoras' aria-expanded="false" aria-controls='bitacoras'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#bitacoras' aria-expanded="false" aria-controls='bitacoras'>
                              Actas de reunión
                            </button>
                          </h5>
                        </div>
                        <div id='bitacoras' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4">
                                <template v-if="!listRolPermisosByUsuario.includes('EsAlumno')">
                                    <dt ref="vinculacioncrear">Selección de alumno</dt>
                                    <dd class="pl-4 mr-2 text-justify">
                                        Para realizar la búsqueda del conjunto de tesistas dirijase a la sección de "Criterios de búsqueda", donde seleccionará al
                                        conjunto de alumnos búscado.
                                    </dd>
                                </template>
                                <template v-if="listRolPermisosByUsuario.includes('bitacoras.crear')">
                                    <dt ref="archivoscomisiones">Ingresar acta de reunión</dt>
                                    <dd class="pl-4 mr-2 text-justify">
                                        Para el ingreso de una nueva acta de reunión diríjase a la sección superior de la página en Actas de reunión y seleccione el
                                        botón con ícono representativo <i class="fas fa-plus-square"></i> titulado como "Ingresar acta de reunión".
                                        <br>
                                        Seleccione el conjunto de alumnos al que quiera desee ingresar la nueva acta de reunión, para luego escribir el acuerdo deseado.
                                    </dd>
                                </template>
                                <template v-if="true">
                                    <dt ref="archivoscomisiones">Ver acta de reunión</dt>
                                    <dd class="pl-4 mr-2 text-justify">
                                        Para acceder a la información del acta seleccione el título o el botón con ícono representativo
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>.
                                    </dd>
                                </template>
                                <template v-if="listRolPermisosByUsuario.includes('bitacoras.editar')">
                                    <dt ref="archivoscomisiones">Editar acta de reunión</dt>
                                    <dd class="pl-4 mr-2 text-justify">
                                        Para realizar una edición del acta de reunión diríjase a la sección bandeja de resultados y seleccione el botón con ícono
                                        representativo <i class="fas fa-pencil-alt"></i> del acta que desee editar.
                                    </dd>
                                </template>
                            </dl>
                            <!--
                                bitacoras.index
                                bitacoras.crear
                                bitacoras.editar
                            -->
                        </div>
                      </div>

                      <div class="card-white" v-if="listRolPermisosByUsuario.includes('comisiones.index')">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#comisiones' aria-expanded="false" aria-controls='comisiones'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#comisiones' aria-expanded="false" aria-controls='comisiones'>
                              Comisiones
                            </button>
                          </h5>
                        </div>
                        <div id='comisiones' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4">
                                <template v-if="true">
                                  <dt ref="vinculacioncrear">Búsqueda de trabajos</dt>
                                  <dd class="pl-4 mr-2 text-justify">
                                    Para realizar una acotación de los trabajos realizados dentro de una comisión, dirijase a la sección "Sistema de búsqueda"
                                    y haga click en esta para desplegar los distintos filtros de búsqueda.
                                  </dd>
                                </template>
                                <template v-if="listRolPermisosByUsuario.includes('comisiones.crear')">
                                  <dt ref="vinculacioncrear">Crear una comisión evaluadora</dt>
                                  <dd class="pl-4 mr-2 text-justify">
                                    Para crear una nueva comisión evaluadora dirijase a la sección de comisiones de "Profesor guía" (tan solo ese rol puede crear
                                    una comisión), busque el trabajo el cual quiera asignarle una comisión evaluadora y use el botón con ícono representativo
                                    <i class="fas fa-plus-circle"></i>.
                                    <br>
                                    La comisión evaluadora también es creada cuando se confirma el trabajo siendo Profesor guía, en el módulo Revisar {{terminoTitulo}}.
                                  </dd>
                                </template>
                                <template v-if="listRolPermisosByUsuario.includes('comisiones.editar')">
                                  <dt ref="vinculacioncrear">Editar una comisión evaluadora</dt>
                                  <dd class="pl-4 mr-2 text-justify">
                                    Para editar una comisión ya creada dirijase a la sección de comisiones de "Profesor guía" (tan solo ese rol puede editar una
                                    comisión), busque el trabajo el cual quiera realizar la edición de la comisión evaluadora y use el botón con ícono representativo
                                    <i class="fas fa-pencil-alt"></i>. En esta sección puede transformar una comisión provisoria a definitiva, además de cambiar los
                                    profesores que pertenecen a la misma comisión.
                                  </dd>
                                </template>
                                <template v-if="listRolPermisosByUsuario.includes('comisiones.crear')">
                                  <dt ref="vinculacioncrear">Eliminar una comisión evaluadora</dt>
                                  <dd class="pl-4 mr-2 text-justify">
                                    Para eliminar una comisión evaluadora dirijase a la sección de comisiones de "Profesor guía" (tan solo ese rol puede eliminar
                                    una comisión), busque el trabajo el cual quiera eliminar la comisión evaluadora y use el botón con ícono representativo
                                    <i class="fas fa-trash-alt"></i>.
                                  </dd>
                                </template>
                                <template v-if="listRolPermisosByUsuario.includes('comisiones.index')">
                                  <dt ref="vinculacioncrear">Descargar documento a revisar</dt>
                                  <dd class="pl-4 mr-2 text-justify">
                                    Para realizar una evaluación de un documento de un trabajo diríjase a la sección de comisiones de "Profesor guía" o "Partícipe",
                                    busque el trabajo el cual quiera evaluar y use el botón con ícono representativo <b>DR</b> (Documento de Revisión).
                                    Descargará el documento y este puede ser evaluado para ser posteriormente ingresado.
                                  </dd>
                                </template>
                                <template v-if="listRolPermisosByUsuario.includes('comisiones.index')">
                                  <dt ref="vinculacioncrear">Ingresar un documento revisado</dt>
                                  <dd class="pl-4 mr-2 text-justify">
                                    Para ingresar un documento revisado para el alumno diríjase a la sección de comisiones de "Profesor guía" o "Partícipe",
                                    busque el trabajo el cual quiera evaluar y use el botón con ícono representativo <i class="fas fa-file-upload"></i>.
                                    En la nueva ventana emergente ingrese el archivo que desee ser revisado y escriba un comentario
                                    que pueda servir de ayuda para la edición.
                                  </dd>
                                </template>
                            </dl>
                            <!--
                            comisiones.index
                            comisiones.crear
                            comisiones.editar
                            comision.verRevision
                            -->
                        </div>
                      </div>

                      <div class="card-white" v-if="listRolPermisosByUsuario.includes('actadefensa.index')">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#actadefensa' aria-expanded="false" aria-controls='actadefensa'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#actadefensa' aria-expanded="false" aria-controls='actadefensa'>
                                Acta de defensa y nota
                            </button>
                          </h5>
                        </div>
                        <div id='actadefensa' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4">
                                <template v-if="listRolPermisosByUsuario.includes('actadefensa.index')">
                                  <dt ref="vinculacioncrear">Agendar defensa de título</dt>
                                  <dd class="pl-4 mr-2 text-justify">
                                      Para <b>agendar</b> una defensa de título diríjase a la sección de "Bandeja de resultados" y use el botón con ícono representativo
                                      <i class="fas fa-calendar-day"></i>. En la nueva ventana emergente, puede insertar fecha, hora y sala donde se realizará la defensa.
                                      <br>
                                      Para <b>editar</b> esta defensa, puede volver a utilizar el mismo botón y precargará los datos para cambiarlos.
                                  </dd>
                                </template>
                                <template v-if="listRolPermisosByUsuario.includes('actadefensa.subirnota')">
                                  <dt ref="vinculacioncrear">Subir nota de defensa</dt>
                                  <dd class="pl-4 mr-2 text-justify">
                                      Para ingresar una nota de defensa diríjase a la sección de "Bandeja de resultados" y use el botón con ícono representativo
                                      <i class="fas fa-graduation-cap"></i>. En esta interfaz podrá ingresar la nota del trabajo, donde podrá establecer una aprobación sin nota, o bien,
                                      una aprobación/desaprobación con nota.
                                  </dd>
                                </template>
                                <template v-if="listRolPermisosByUsuario.includes('actadefensa.subiracta')">
                                  <dt ref="vinculacioncrear">Subir acta de defensa</dt>
                                  <dd class="pl-4 mr-2 text-justify">
                                      Para subir un documento de acta de defensa diríjase a la sección de "Bandeja de resultados" y use el botón con ícono representativo
                                      <i class="fas fa-file-upload"></i>. Se iniciará un nuevo módulo para ingresar el documento, donde se solicitará el documento por la interfaz.
                                  </dd>
                                </template>
                                <template v-if="listRolPermisosByUsuario.includes('actadefensa.registroDocumento')">
                                  <dt ref="vinculacioncrear">Ingresar registros DARA</dt>
                                  <dd class="pl-4 mr-2 text-justify">
                                      Para mantener los datos de los ingresos del registro a DARA, se implementa una sección que facilita la copia de datos de todos los alumnos.
                                      Para acceder a esta interfaz diríjase a la sección de "Bandeja de resultados" y use el botón con ícono representativo
                                      <i class="fas fa-list"></i>.
                                      <br>
                                      Se abrirá un módulo con los nombres de los alumnos, donde tendrá un botón con ícono representativo <i class="fas fa-address-card"></i>, este abrirá
                                      una ventana emergente donde podrá copiar a los datos a través de los botones con ícono representativo <i class="fas fa-copy"></i>.
                                      <br>
                                      Para finalizar, seleccione el switch del alumno el cual ya ha sido registrado, para mantener los datos del usuario como registrado.
                                  </dd>
                                </template>
                            </dl>
                            <!--
                                actadefensa.index
                                actadefensa.subiracta
                                actadefensa.subirnota
                            -->
                        </div>
                      </div>

                      <div class="card-white" v-if="listRolPermisosByUsuario.includes('documentos.index')">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#documentos' aria-expanded="false" aria-controls='documentos'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#documentos' aria-expanded="false" aria-controls='documentos'>
                                Documentos de alumno
                            </button>
                          </h5>
                        </div>
                        <div id='documentos' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4">
                                <template>
                                  <dt ref="vinculacioncrear">Resumen</dt>
                                  <dd class="pl-4 mr-2 text-justify">
                                      Este módulo está creado para la descarga de archivos de los trabajos de los alumnos, en esta sección puede hacer búsqueda de
                                      trabajos mediante la sección "Criterios de búsqueda".
                                      <br>
                                      Cada trabajo tiene acceso a distintos documentos del propio trabajo, estos documentos pueden ser accedidos mediante los botones
                                      con ícono representativo <b>DF</b>, <b>CE</b>, <b>AD</b>, <b>{{terminoTitulo}}</b> y <b>MR</b>, los cuales son Documento final,
                                      Constancia de examen, Acta de defensa, {{terminoTituloExtendido}}, Memo de revisión (autogenerado).
                                  </dd>
                                </template>
                            </dl>
                            <!-- documentos.index -->
                        </div>
                      </div>

                      <div class="card-white" v-if="false && listRolPermisosByUsuario.includes('index.tesisfinal')">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#tesisfinal' aria-expanded="false" aria-controls='tesisfinal'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#tesisfinal' aria-expanded="false" aria-controls='tesisfinal'>
                              Registros de documentos finales
                            </button>
                          </h5>
                        </div>
                        <div id='tesisfinal' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4">
                                <template v-if="listRolPermisosByUsuario.includes('index.tesisfinal')">
                                  <dt ref="vinculacioncrear">Resumen</dt>
                                  <dd class="pl-4 mr-2 text-justify">

                                  </dd>
                                </template>
                            </dl>
                            <!--
                                index.tesisfinal
                                registrar.tesis
                                editar.tesisfinal
                            -->
                        </div>
                      </div>

                      <div class="card-white" v-if="listRolPermisosByUsuario.includes('abandono.index')">
                        <div class="card-header" id='heading1'>
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" data-target='#abandono' aria-expanded="false" aria-controls='abandono'>
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" data-target='#abandono' aria-expanded="false" aria-controls='abandono'>
                                {{terminoTitulo}}s en abandono
                            </button>
                          </h5>
                        </div>
                        <div id='abandono' class="collapse" aria-labelledby='heading1' data-parent="#accordion">
                            <dl class="pl-4">
                                <template v-if="listRolPermisosByUsuario.includes('abandono.index')">
                                  <dt ref="vinculacioncrear">Resumen</dt>
                                  <dd class="pl-4 mr-2 text-justify">
                                    Este módulo ha sido creado para descartar trabajos que no han progresado en un tiempo e impidir la inserción de nuevos documentos,
                                    estos trabajos son declarados en estado de abandono y cesará toda actividad, para cambiar un trabajo que actualmente está en estado
                                    de desarrollo a un trabajo en abandono, nos dirigimos a lasección de "Bandeja de resultados" y buscaremos el trabajo que debe ser
                                    cambiado de estado, seleccione el botón con ícono representativo <i class="fas fa-box"></i>, en caso de transformar nuevamente este trabajo
                                    como pendiente puede utilizar el botón con ícono representativo <i class="fas fa-box-open"></i>.
                                  </dd>
                                </template>
                            </dl>
                            <!-- abandono.index -->
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" :class="{ show: modalAddDetailsUser }" :style="modalAddDetailsUser ? mostrarModal : ocultarModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content scrollTable">
          <div class="modal-header">
            <h5 class="modal-title"><b>Ingreso de datos faltantes</b></h5>
            <button class="btn btn-danger w-20" @click.prevent="logout">Cerrar sesión</button>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Escuela perteneciente</label>
                        <div class="col-md-9">
                            <el-select v-model="detailsUser.idEscuela"
                            filterable
                            placeholder="Seleccione escuela"
                            clearable>
                            <el-option
                                v-for="item in listEscuela"
                                :key="item.id"
                                :label="item.nombre"
                                :value="item.id">
                            </el-option>
                            </el-select>
                        </div>
                    </div>
                </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Dirección</label>
                  <div class="col-md-9">
                    <input
                      type="text"
                      class="form-control"
                      v-model="detailsUser.direccion"
                    />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Teléfono</label>
                  <div class="col-md-9">
                    <input
                      type="text"
                      class="form-control"
                      v-model="detailsUser.telefono"
                    />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Fecha de ingreso a la carrera</label>
                  <div class="col-md-9">
                    <el-date-picker
                    v-model="detailsUser.f_entrada"
                    type="month"
                    placeholder="Fecha de inicio"
                    format="dd/MM/yyyy"
                    value-format="yyyy-MM-dd"
                    :picker-options="pickerOptions"
                    @change="selectStart">
                    </el-date-picker>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Fecha de término de asignaturas</label>
                  <div class="col-md-9">
                    <el-date-picker
                    v-model="detailsUser.f_salida"
                    type="month"
                    placeholder="Fecha de término"
                    format="dd/MM/yyyy"
                    value-format="yyyy-MM-dd"
                    :picker-options="endOption">
                    </el-date-picker>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <button class="btn btn-success w-100" @click="addDetailsUser">
                  Ingresar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

</div>
</template>

<script>
export default {
props: ['usuario'],
    data(){
        return{
            listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
            listRolByUser: JSON.parse(localStorage.getItem('rolUser')),
            myOwnUser: JSON.parse(localStorage.getItem('authUser')),
            terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
            terminoTituloExtendido: JSON.parse(localStorage.getItem('TerminoDeTituloExtendido')),
            rolActivo: JSON.parse(localStorage.getItem('rolActivo')),
            detailsUser: {
                idEscuela: '',
                direccion: '',
                telefono: '',
                f_entrada: '',
                f_salida: ''
            },
            listPermisos:[],
            listEscuela: [],
            modalAddDetailsUser: false,
            mostrarModal: {
                display: 'block',
                background: '#0000006b'
            },
            ocultarModal: {
                display: 'none',
            },
            pickerOptions: {
                disabledDate(time) {
                    return time.getTime() > Date.now();
                }
            },
            endOption: {
            },
        }
    },
    mounted(){
        EventBus.$emit('navegar', 'Sistema de Gestión y Administración de Documentos');
        EventBus.$on('refresh', x => {this.init()});
        this.init();
    },
    beforeDestroy(){
      EventBus.$off('refresh');
    },
    methods:{
        init(){
          this.inicializacion();
          this.datosPendientes();
          this.focusItem();
        },
        logout(){
            this.fullscreenLoading = true;
            var url='/authenticate/logout'
            axios.post(url).then(response => {
                if(response.data.code == 204){
                    window.location.href = '/sigad/';
                    //location.reload();
                    localStorage.clear();
                    this.fullscreenLoading = false;
                }
            })
        },
        inicializacion () {
            const yearCalculated = 5*365*24*60*60*1000;
            const month = (new Date().getMonth()) * 30*24*60*60*1000;
            this.detailsUser.f_entrada = new Date(Date.now() - (yearCalculated + month));
            this.detailsUser.f_salida = new Date(Date.now() - (month));
            this.selectStart();
        },
        datosPendientes() {
            this.fullscreenLoading = true;
            let atributoFaltante = false;
            if (this.listRolByUser[0].name === 'Alumno') {
                if (!this.myOwnUser.id_escuela || !this.myOwnUser.direccion ||
                    !this.myOwnUser.f_ingreso || !this.myOwnUser.f_salida ||
                    !this.myOwnUser.telefono) {
                    atributoFaltante = true;
                }
            }
            this.fullscreenLoading = false;
            if (atributoFaltante) {
                this.modalAddDetailsUser = true;
                this.getListarEscuela();
            }
        },
        mostrarModalIngresoDetalle () {
            this.modalAddDetailsUser = !this.modalAddDetailsUser;
        },
        addDetailsUser() {
            if (!this.verificacionDatos()) {
                return;
            } else {
                this.fullscreenLoading = true;
                const url = '/administracion/usuario/setEditarDetalleAlumno';
                axios.post(url, this.detailsUser)
                .then(response => {
                    this.modalAddDetailsUser = false;
                    this.fullscreenLoading = false;
                    localStorage.setItem('authUser', JSON.stringify(response.data));
                    this.myOwnUser = JSON.parse(localStorage.getItem('authUser'));
                    Swal.fire({
                        icon: "success",
                        title: "Datos ingresados",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                })
                .catch(response=>{
                        Swal.fire({
                        icon: 'error',
                        title: 'Error al ingresar datos',
                        showConfirmButton: false,
                        timer: 1500
                    })
                })
            }
        },
        getListarEscuela() {
            this.fullscreenLoading = true;
            const url = '/administracion/escuelas/getListarEscuelas';
            axios.get(url, {
            }).then(response => {
                this.listEscuela = response.data;
                this.fullscreenLoading = false;
            })
        },
        selectStart() {
            this.endOption = {
                disabledDate: (time) => {
                    return time.getTime() < Date.parse(this.detailsUser.f_entrada) || time.getTime() > Date.now();
                }
            };
        },
        verificacionDatos() {
            if (!this.detailsUser.idEscuela || !this.detailsUser.f_entrada ||
                !this.detailsUser.f_salida || !this.detailsUser.direccion ||
                !this.detailsUser.telefono) {
                    alert('Todos los campos son requeridos');
                    return false;
            }
            return true;
        },
        focusItem() {
            if (this.$attrs.open) {
                var open = this.$refs[this.$attrs.open];
                open.children[1].className = 'collapse show';
                setTimeout(x => {
                    const focus = this.$refs[this.$attrs.focus];
                    const top = focus.offsetTop;
                    if (focus) {
                        window.scrollBy({
                            top: top + 100,
                            behavior: 'smooth'
                        });
                    }
                },350);
            }
        }
    },
}
</script>

<style>
.card-body .border-success{
  border-color: #7b7b7b !important;
}
</style>
