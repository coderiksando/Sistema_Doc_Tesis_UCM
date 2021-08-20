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
        <div class="card-body px-0">
          <div class="container-fluid">
            <div class="card-body px-0">
                <div class="row">
                  <div class="col-md-12">
                    <div id="accordion">
                      <div class="card-white" v-for="(item, index) in placeholder" :key="index">
                        <div class="card-header" v-bind:id="'heading'+index">
                          <h5 class="mb-0">
                            <a class="btn btn-outline-primary" data-toggle="collapse" v-bind:data-target="'#collapse'+index" aria-expanded="false" v-bind:aria-controls="'collapse'+index">
                                <i class="fa fa-play-circle" aria-hidden="true"></i>
                            </a>
                            <button class="btn btn-link" data-toggle="collapse" v-bind:data-target="'#collapse'+index" aria-expanded="false" v-bind:aria-controls="'collapse'+index">
                              {{titulos[index]}}
                            </button>
                          </h5>
                        </div>

                        <div v-bind:id="'collapse'+index" class="collapse" v-bind:aria-labelledby="'heading'+index" data-parent="#accordion">
                          <div class="card-body">
                            <!-- <div v-text="item" style="white-space: pre-wrap"></div> -->
                            <LazyYoutube src="https://www.youtube.com/watch?v=FBarFchwFDY" />
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
            placeholder: [1,2,3,4,5],
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
            titulos: [
              'Tutorial inscripción de formulario.',
              'Tutorial visualización y descarga de avances.',
              'Tutorial creación de bitácoras.',
              'Tutorial creación y modificación de comisiones',
              'Tutorial visualización de notas pendientes.'
            ]
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
