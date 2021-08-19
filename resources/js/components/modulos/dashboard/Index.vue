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
            <!-- <template v-if="listRolPermisosByUsuario.includes('tesis.crear') "> -->
            <template>
              <div class="card card-success">
                <div class="card-header d-flex justify-content-center">
                  <!-- <h1 class="card-title">{{terminoTituloExtendido}}</h1> -->
                </div>
                <div class="card-body d-flex justify-content-center border border-success">
                    <div class="row text-justify">
                      <div class="col-md-12">

                        <!-- <div id="accordion">
                            <div class="card" :key="'guia'+index">
                                <div class="card-body" :id="'heading'+index">
                                <h3 class="mb-0">
                                    <button class="btn btn-link col-md-12 noPadNoMar d-flex" data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="false" :aria-controls="'collapse'+index">
                                        <div title="Sección expandible" class="col-md-1"><a class="btn btn-outline-primary"><i class="fas fa-plus-circle"></i></a></div>
                                        <div title="Sección expandible" class="col-md-8 noPadNoMar"><p class="float-left">{{moment(item.updated_at).format("DD-MM-YYYY") + ', ' + item.titulo.slice(0, 40)}}</p></div>
                                        <div class="col-md-3 noPadNoMar">
                                            <template v-if="!item.comisiones">
                                                <router-link title="Crear comisión" class="btn boton btn-info" :to="{name:'comisiones.crear', params:{id: item.id}}">
                                                    <i class="fas fa-plus-circle"></i>
                                                </router-link>
                                            </template>
                                            <template v-if="item.comisiones">
                                                <router-link title="Editar comisión" class="btn boton btn-info" :to="{name:'comisiones.editar', params:{id: item.comisiones.id}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </router-link>
                                            </template>
                                            <router-link :title="'Ver '+terminoTitulo" class="btn boton btn-primary" :to="{name:'tesis.ver', params:{id: item.id}}">
                                                <i class="fas fa-eye"></i>
                                            </router-link>
                                            <button :title="'Descargar documento de '+terminoTitulo" class="btn boton btn-warning" @click.prevent="descargarDocumento(item.id)" v-loading.fullscreen.lock="fullscreenLoading">
                                                <i class="fas fa-file-download"></i>
                                            </button>
                                            <template v-if="item.comisiones">
                                                <button title="Ingresar revisión" class="btn boton btn-success" @click.prevent="modalInsercionDocumento(item)">
                                                    <i class="fas fa-file-upload"></i>
                                                </button>
                                            </template>
                                        </div>
                                    </button>
                                </h3>
                                </div>

                                <div :id="'collapse'+index" class="collapse" :aria-labelledby="'heading'+index" data-parent="#accordion">
                                <div class="card-footer">
                                    <dl class="row">
                                        <dt class="col-md-4">Alumnos integrantes:</dt>
                                        <template v-if="item.fit__user">
                                            <dd class="col-md-8" v-for="(fitUser, index2) in item.fit__user" :key="index2">
                                                {{fitUser.user.nombres.split(' ')[0]+' '+fitUser.user.apellidos.split(' ')[0]}}
                                            </dd>
                                        </template>
                                        <dt class="col-md-4">Título extendido:</dt>
                                        <dd class="col-md-8">{{item.titulo}}</dd>
                                        <dt class="col-md-4">Descripción:</dt>
                                        <dd class="col-md-8">{{item.descripcion}}</dd>
                                        <template v-if="item.user__p__coguia">
                                            <dt class="col-md-4">Prof. Co-guía:</dt>
                                            <dd class="col-md-8">{{item.user__p__coguia.nombres.split(' ')[0] + ' ' + item.user__p__coguia.apellidos.split(' ')[0]}}</dd>
                                        </template>
                                        <dt class="col-md-4">Comisión evaluadora:</dt>
                                        <dd class="col-md-8">
                                            <dl v-if="item.comisiones" class="row">
                                                <template v-if="item.comisiones.user_p1">
                                                    <dt class="col-md-4">1° Prof. Interno:</dt>
                                                    <dd class="col-md-8">{{item.comisiones.user_p1.nombres.split(' ')[0]+' '+item.comisiones.user_p1.apellidos.split(' ')[0]}}</dd>
                                                </template>
                                                <template v-if="item.comisiones.user_p2">
                                                    <dt class="col-md-4">2° Prof. Interno:</dt>
                                                    <dd class="col-md-8">{{item.comisiones.user_p2.nombres.split(' ')[0]+' '+item.comisiones.user_p2.apellidos.split(' ')[0]}}</dd>
                                                </template>
                                                <template>
                                                    <dt class="col-md-4">Prof. Externo:</dt>
                                                    <dd class="col-md-8">{{item.comisiones.p_externo}}</dd>
                                                </template>
                                            </dl>
                                        </dd>
                                        <dt class="col-md-4">Tipo de documento:</dt>
                                        <dd class="col-md-8">{{item.tipo}}</dd>
                                    </dl>
                                </div>
                                </div>
                            </div>
                        </div> -->

                        <!-- <h5 class="card-text">La principal función como estudiante sera completar el formulario de inscripción
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
                        <img src="/img/DescargarFIT.png" class="img-fluid" alt="Responsive image"> -->
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
