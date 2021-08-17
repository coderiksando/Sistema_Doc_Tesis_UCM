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
      <div class="card text-center">
        <div class="card-body">
          <div class="container-fluid">

            <!-- Cards Alumnos -->

            <!-- inscribir tesis card -->
            <template v-if="listRolPermisosByUsuario.includes('tesis.crear') ">
              <div class="card card-success">
                <div class="card-header d-flex justify-content-center">
                  <h1 class="card-title">{{terminoTituloExtendido}}</h1>
                </div>
                <div class="card-body d-flex justify-content-center border border-success">
                    <div class="row text-justify">
                      <div class="col-md-12">
                        <h5 class="card-text">La principal función como estudiante sera completar el formulario de inscripción
                          de tesis en donde debes definir parametros como profesor guía, título, contribución esperada y otros similares a los utilizados
                          en el formulario tradicional de inscripción. </h5>
                        <img src="/img/ingresarFIT.png" class="img-fluid" alt="Responsive image">
                        <br>
                        <br>
                        <h5 class="card-text">Una vez que completes tu {{terminoTitulo}} podras revisar su estado en espera de la aprobacion de tu profesor guía,
                          mientras este en estado "pendiente" podras seguir editandolo, ademas podras darte cuenta que el sistema no te permitira ingresar
                          un nuevo formulario </h5>
                        <img src="/img/EsperaFit.png" class="img-fluid" alt="Responsive image">
                        <br>
                        <br>
                        <h5 class="card-text">Finalmente, cuando tu {{terminoTitulo}} es aprobado por el profesor guía el sistema te permitira descargar una copia de este en formato PDF. </h5>
                        <img src="/img/DescargarFIT.png" class="img-fluid" alt="Responsive image">
                      </div>
                    </div>
                </div>
              </div>
            </template>
            <!-- avances card -->
            <template v-if="listRolPermisosByUsuario.includes('avances.crear')">
              <div class="card card-success">
                <div class="card-header d-flex justify-content-center">
                  <h3 class="card-title ">Subir avances de tesis</h3>
                </div>
                <div class="card-body d-flex justify-content-center border border-success">
                    <div class="row text-justify">
                      <div class="col-md-12">
                        <h5 class="card-text"> Cuando ya han aprobado tu formulario de inscripción podras ingresar a la seccion de avances, donde podras
                          visualizar los avances de tesis que ya hayas subido o subir uno nuevo, ademas veras la opcion de subir el pdf final
                          de tu tesis las cual se desbloquea una vez que apruebas tu tesis, este pdf final es el que estara disponible para todos
                          los usuarios que lo requieran y se posible descargarlo desde nuestro home. </h5>
                        <img src="/img/AvancesIndex.png" class="img-fluid" alt="Responsive image" >
                        <br>
                        <br>
                        <h5 class="card-text">Para subir un avance debes seleccionar el archivo pdf ademas de
                          indicar una pequeña descripcion de su contenido, el sistema le avisara mediante email a
                          tu profesor guía que haz subido un avance de tesis. </h5>
                        <img src="/img/AvancesCreate.png" class="img-fluid" alt="Responsive image" >
                      </div>
                    </div>
                </div>
              </div>
            </template>
            <!-- bitacoras card -->
            <template v-if="listRolPermisosByUsuario.includes('EsAlumno','bitacoras.index')">
            <div class="card card-success">
              <div class="card-header d-flex justify-content-center">
                <h3 class="card-title">Bitacoras</h3>
              </div>
              <div class="card-body d-flex justify-content-center border border-success">
                  <div class="row text-justify">
                    <div class="col-md-12">
                      <h5 class="card-text"> Las bitacoras son el instrumento mediante el cual el profesor dejara registro de
                        los acuerdos o de los avances que tenga el alumno en el desarrllo de su tesis. </h5>
                      <img src="/img/BitacorasIndexAlumno.png" class="img-fluid" alt="Responsive image" >
                    </div>
                  </div>
              </div>
            </div>
            </template>
            <!-- Vinculaciones alumno card -->
            <template v-if="listRolPermisosByUsuario.includes('EsAlumno','vinculaciones.index')">
              <div class="card card-success ">
                <div class="card-header card-header d-flex justify-content-center">
                  <h3 class="card-title">Vinculaciones</h3>
                </div>
                <div class="card-body d-flex justify-content-center border border-success">
                    <div class="row text-justify">
                      <div class="col-md-12">
                        <h5 class="card-text">En el apartado de vinculaciones podras ver programas a los cuales podria estar asociada tu tesis,
                          ya sean fondos consursables, empresas o proyectos a nivel de tu comunidad. </h5>
                        <img src="/img/VinculacionesIndex.png" class="img-fluid" alt="Responsive image" >
                      </div>
                    </div>
                </div>
              </div>
            </template>
            <!-- Notas pendientes card -->
            <template v-if="listRolPermisosByUsuario.includes('notaspendientes.create') ">
              <div class="card card-success">
                <div class="card-header card-header d-flex justify-content-center">
                  <h3 class="card-title">Ingreso de Nota pendiente</h3>
                </div>
                <div class="card-body d-flex justify-content-center border border-success">
                    <div class="row text-justify">
                      <div class="col-md-12">
                        <h5 class="card-text">Con la finalidad de hacer un seguimiento efectivo al proceso de tesis de los alumnos el sistema te
                          permite registrar una solicitud de nota pendiente y prorroga, esta no reemplaza la solicitud  que debe ser realizada en DARA.</h5>
                        <img src="/img/solicitudNP.png" class="img-fluid" alt="Responsive image" >
                      </div>
                    </div>
                </div>
              </div>
            </template>
            <!-- Cierre Cards Alumnos -->
            <!-- new card -->
            <!-- Cards Profesores Fit -->
            <template v-if="listRolPermisosByUsuario.includes('tesis.aprobar') ">
              <div class="card card-success">
                <div class="card-header card-header d-flex justify-content-center">
                  <h3 class="card-title">Aprobar {{terminoTituloExtendido}}</h3>
                </div>
                <div class="card-body d-flex justify-content-center border border-success">
                    <div class="row text-justify">
                      <div class="col-md-12">
                        <h5 class="card-text">En el apartado de tesis, su rol de profesor le permitira revisar
                          los fomularios que han inscrito sus pupilos, revisarlos y segun corresponda aceptarlos o rechazarlos.</h5>
                        <img src="/img/AprobarFit.png" class="img-fluid" alt="Responsive image" >
                        <br>
                        <br>
                        <h5 class="card-text">Cuando el {{terminoTitulo}} es aprobado el sistema te permitira descargar una copia de este en formato PDF. </h5>
                        <img src="/img/DescargarFIT.png" class="img-fluid" alt="Responsive image">
                      </div>
                    </div>
                </div>
              </div>
            </template>
            <!-- Cards Profesores Avances -->
            <template v-if="listRolPermisosByUsuario.includes('tesis.aprobar','avances.index') ">
              <div class="card card-success">
                <div class="card-header card-header d-flex justify-content-center">
                  <h3 class="card-title">Revisar avances de alumnos</h3>
                </div>
                <div class="card-body d-flex justify-content-center border border-success">
                    <div class="row text-justify">
                      <div class="col-md-12">
                        <h5 class="card-text">En el apartado de avances, podras consultar y descargar los avances que han subido tus alumnos
                          hasta el momento.</h5>
                        <img src="/img/avancesProfesor.png" class="img-fluid" alt="Responsive image" >
                      </div>
                    </div>
                </div>
              </div>
            </template>
            <!-- Cards Profesores Bitacoras-->
            <template v-if="listRolPermisosByUsuario.includes('bitacoras.crear','bitacoras.index') ">
              <div class="card card-success">
                <div class="card-header card-header d-flex justify-content-center">
                  <h3 class="card-title">Ingresar bitacoras de tesis</h3>
                </div>
                <div class="card-body d-flex justify-content-center border border-success">
                    <div class="row text-justify">
                      <div class="col-md-12">
                        <h5 class="card-text">Las bitacoras permitiran dejar un registro de las reuniones
                          que los profesor tengan con sus alumnos, a modo de dejar constancia del trabajo que se lleva hasta el momento.</h5>
                        <img src="/img/BitacorasIndex.png" class="img-fluid" alt="Responsive image">
                        <br>
                        <br>
                        <h5 class="card-text">Formulario de ingreso de bitacora.</h5>
                        <img src="/img/BitacorasCreate.png" class="img-fluid" alt="Responsive image">
                      </div>
                    </div>
                </div>
              </div>
            </template>
            <!-- Cards Profesores Comisiones -->
            <template v-if="listRolPermisosByUsuario.includes('comisiones.index')">
              <div class="card card-success">
                <div class="card-header card-header d-flex justify-content-center">
                  <h3 class="card-title">Ingreso de comisiones</h3>
                </div>
                <div class="card-body d-flex justify-content-center border border-success">
                    <div class="row text-justify">
                      <div class="col-md-12">
                        <h5 class="card-text">En la seccion de comisiones el profesor podra visualizar las comisiones en las
                          que es participe, ademas de las creadas por el.</h5>
                        <img src="/img/ComisionesIndex.png" class="img-fluid" alt="Responsive image" >
                        <br>
                        <br>
                        <h5 class="card-text">Para ingresar una comision es necesario indicar el alumno del cual crearemos una tesis, junto con eso, seleccionar
                          dentro de los profesores registrados en el sistema cuales seran parte de una comision o de ser necesario inscribir
                          un profesor externo indicando campos como nombre, email e institucion.</h5>
                          <img src="/img/ComisionesCreate.png" class="img-fluid" alt="Responsive image" >
                      </div>
                    </div>
                </div>
              </div>
            </template>
            <!-- Cards director secretaria nota y acta   -->
            <template v-if="listRolPermisosByUsuario.includes('actadefensa.index')">
            <div class="card card-success">
              <div class="card-header card-header d-flex justify-content-center">
                <h3 class="card-title">Ingresar Notas y actas de defensa </h3>
              </div>
              <div class="card-body d-flex justify-content-center border border-success">
                  <div class="row text-justify">
                    <div class="col-md-12">
                      <h5 class="card-text">En la seccion de notas y actas de defensa los usuarios de tipo Director y secretaria podran buscar y seleccionar entre los
                       alumnos registrado en el sistema para luego disponer de la opcion de ingresar la nota de defensa de tesis y ademas subir el acta correspondiente.</h5>
                      <img src="/img/actaDefensaIndex.png" class="img-fluid" alt="Responsive image" >
                      <br>
                      <br>
                      <h5 class="card-text">Ingreso de nota de tesis: dependiendo de esta se cambiara el estado de la tesis a reprobada o aprobada y por consiguiente permitira
                        al alumno subir el pdf  final al sistema para que este dispoble para los demas estudiantes.</h5>
                      <img src="/img/subirnota.png" class="img-fluid" alt="Responsive image" >
                      <br>
                      <br>
                      <h5 class="card-text">El sistema tambien permite subir una copia en pdf del acta de defensa en lo posible ya firmada por los profesores participantes.</h5>
                      <img src="/img/subiracta.png" class="img-fluid" alt="Responsive image" >
                    </div>
                  </div>
              </div>
            </div>
            </template>
            <!-- Cards secretaria director Documentos -->
            <template v-if="listRolPermisosByUsuario.includes('documentos.index')">
              <div class="card card-success">
                <div class="card-header card-header d-flex justify-content-center">
                  <h3 class="card-title">Descargar documentos del alumno</h3>
                </div>
                <div class="card-body d-flex justify-content-center border border-success">
                    <div class="row text-justify">
                      <div class="col-md-12">
                        <h5 class="card-text">En el apartado de documentos del alumno los usuarios de tipo secretaria o director podran buscar y seleccionar alumnos
                          registrados en el sistema con el objetivo de descargar los documentos referentes al proceso de tesis que tengas asociados tales como
                          {{terminoTituloExtendido}}({{terminoTitulo}}) y acta de defensa ademas, de generar un memo de revision de tesis el cual calculara los
                          dias habiles de revision a partir del momento en el que es generado.</h5>
                        <img src="/img/DocumentosIndex.png" class="img-fluid" alt="Responsive image" >
                      </div>
                    </div>
                </div>
              </div>
            </template>
            <!-- Cards Profesores -->
            <template v-if="listRolPermisosByUsuario.includes('reportes.reportefit')">
              <div class="card card-success">
                <div class="card-header card-header d-flex justify-content-center">
                  <h3 class="card-title">Reportes en formato excel</h3>
                </div>
                <div class="card-body d-flex justify-content-center border border-success">
                    <div class="row text-justify">
                      <div class="col-md-12">
                        <h5 class="card-text">El usuario con rol Coordinador podra hacer una busqueda detallada  de la informacion de los alumnos
                          registrados en el sistema ya sea clasificandolos en base a la escuela a la cual pertenecen, profesor guía, estado de tesis y demas,
                          junto con esto podra ver los registros individuales de los avances y bitacoras asociadas, de ser necesario podra exportar los resultados
                          de su busqueda a excel.</h5>
                        <img src="/img/Reportes.png" class="img-fluid" alt="Responsive image" >
                      </div>
                    </div>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" :class="{ show: modalAddDetailsUser }" :style="modalAddDetailsUser ? mostrarModal : ocultarModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content scrollTable">
          <div class="modal-header">
            <h5 class="modal-title"><b>Ingreso de datos faltantes</b></h5>
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
        this.inicializacion();
        this.datosPendientes();
    },
    methods:{
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
        }
    },
}
</script>

<style>
.card-body .border-success{
  border-color: #7b7b7b !important;
}
</style>
