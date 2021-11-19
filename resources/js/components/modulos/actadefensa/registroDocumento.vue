<template>
    <div class="card-body">
        <div class="container-fluid">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Integrantes del {{terminoTitulo}}</h3>
                </div>
                <div class="card-body" v-for="(itemFitUser, index) in documentoTitulo.fit__user" :key="index">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group row">
                                <button class="btn boton btn-primary" title="Datos de alumno" @click.prevent="mostrarModal(itemFitUser)">
                                    <i class="fas fa-address-card"></i>
                                </button>
                                <label class="col-md-8 col-form-label">{{itemFitUser.user.nombres+' '+itemFitUser.user.apellidos}}</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="switch">
                                <input v-model="itemFitUser.reg_doc" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-4 offset-5">
                            <button class="btn btn-flat btn-info btnWidth" @click.prevent="guardarDatos" v-loading.fullscreen.lock="fullscreenLoading">
                                <i class="fa fa-save"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <template v-if="showModal">
      <div class="swal2-container swal2-center swal2-backdrop-show" style="overflow-y: auto;" @mousedown.prevent="showModal=false">
        <div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-icon-warning swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: flex; z-index: 2; width: 70% !important; min-width: 360px !important" v-on:mousedown.stop>
          <div class="swal2-header">
            <h2 class="swal2-title" id="swal2-title" style="display: flex;">Ayuda de información de registros DARA</h2>
            <button type="button" class="swal2-close" aria-label="Close this dialog" style="display: none;">×</button>
          </div>
          <div class="swal2-content text-left">
            <br>
            <dl class="row">
                <dt class="col-md-2">Trabajo de conclusión</dt>
                <dd class="col-md-10">
                    <dl class="row">
                        <dt class="col-md-4">Título</dt>
                        <dd class="col-md-7">
                            <div class="elipsis">{{documentoTitulo.titulo}}</div>
                        </dd>
                        <button class="col-md-1 boton btn btn-secondary" title="Copiar al portapapeles"
                            v-clipboard:copy="documentoTitulo.titulo"
                            v-clipboard:success="onCopy"
                            v-clipboard:error="onError">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dl>
                    <dl class="row">
                        <dt class="col-md-4">Carrera</dt>
                        <dd class="col-md-7">{{documentoTitulo.escuela.nombre}}</dd>
                        <button class="col-md-1 boton btn btn-secondary" title="Copiar al portapapeles"
                            v-clipboard:copy="documentoTitulo.escuela.nombre"
                            v-clipboard:success="onCopy"
                            v-clipboard:error="onError">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dl>
                </dd>
            </dl>
            <dl class="row">
                <dt class="col-md-2">Alumno</dt>
                <dd class="col-md-10">
                    <dl class="row">
                        <dt class="col-md-4">Rut</dt>
                        <dd class="col-md-7">{{selectUser.user.rut}}</dd>
                        <button class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                            v-clipboard:copy="selectUser.user.rut"
                            v-clipboard:success="onCopy"
                            v-clipboard:error="onError">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dl>
                    <dl class="row">
                        <dt class="col-md-4">Nombre</dt>
                        <dd class="col-md-7">{{selectUser.user.nombres}}</dd>
                        <button class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                            v-clipboard:copy="selectUser.user.nombres"
                            v-clipboard:success="onCopy"
                            v-clipboard:error="onError">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dl>
                    <dl class="row">
                        <dt class="col-md-4">Apellido</dt>
                        <dd class="col-md-7">{{selectUser.user.apellidos}}</dd>
                        <button class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                            v-clipboard:copy="selectUser.user.apellidos"
                            v-clipboard:success="onCopy"
                            v-clipboard:error="onError">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dl>
                    <dl class="row">
                        <dt class="col-md-4">Fecha de nacimiento</dt>
                        <dd class="col-md-7">{{(selectUser.user.birthday) ? selectUser.user.birthday :'No registrado'}}</dd>
                        <button v-if="selectUser.user.birthday" class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                            v-clipboard:copy="selectUser.user.birthday"
                            v-clipboard:success="onCopy"
                            v-clipboard:error="onError">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dl>
                    <dl class="row">
                        <dt class="col-md-4">E-mail</dt>
                        <dd class="col-md-7">{{selectUser.user.email}}</dd>
                        <button class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                            v-clipboard:copy="selectUser.user.email"
                            v-clipboard:success="onCopy"
                            v-clipboard:error="onError">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dl>
                    <dl class="row">
                        <dt class="col-md-4">Dirección</dt>
                        <dd class="col-md-7">{{(selectUser.user.direccion)? selectUser.user.direccion:'No registrado'}}</dd>
                        <button v-if="selectUser.user.direccion" class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                            v-clipboard:copy="selectUser.user.direccion"
                            v-clipboard:success="onCopy"
                            v-clipboard:error="onError">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dl>
                    <dl class="row">
                        <dt class="col-md-4">Teléfono</dt>
                        <dd class="col-md-7">{{(selectUser.user.telefono)? selectUser.user.telefono:'No registrado'}}</dd>
                        <button v-if="selectUser.user.telefono" class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                            v-clipboard:copy="selectUser.user.telefono"
                            v-clipboard:success="onCopy"
                            v-clipboard:error="onError">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dl>
                </dd>
            </dl>
            <dl class="row">
                <dt class="col-md-2">Profesor Guía</dt>
                <dd class="col-md-10">
                    <dl class="row">
                        <dt class="col-md-4">Rut</dt>
                        <dd class="col-md-7">{{documentoTitulo.user__p__guia.rut}}</dd>
                        <button class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                            v-clipboard:copy="documentoTitulo.user__p__guia.rut"
                            v-clipboard:success="onCopy"
                            v-clipboard:error="onError">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dl>
                    <dl class="row">
                        <dt class="col-md-4">Nombre completo</dt>
                        <dd class="col-md-7">{{documentoTitulo.user__p__guia.nombres+' '+documentoTitulo.user__p__guia.apellidos}}</dd>
                        <button class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                            v-clipboard:copy="documentoTitulo.user__p__guia.nombres+' '+documentoTitulo.user__p__guia.apellidos"
                            v-clipboard:success="onCopy"
                            v-clipboard:error="onError">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dl>
                </dd>
            </dl>
            <dl class="row" v-if="documentoTitulo.user__p__coguia">
                <dt class="col-md-2">Profesor Co-Guía</dt>
                <dd class="col-md-10">
                    <dl class="row">
                        <dt class="col-md-4">Rut</dt>
                        <dd class="col-md-7">{{documentoTitulo.user__p__coguia.rut}}</dd>
                        <button class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                            v-clipboard:copy="documentoTitulo.user__p__coguia.rut"
                            v-clipboard:success="onCopy"
                            v-clipboard:error="onError">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dl>
                    <dl class="row">
                        <dt class="col-md-4">Nombre completo</dt>
                        <dd class="col-md-7">{{documentoTitulo.user__p__coguia.nombres+' '+documentoTitulo.user__p__coguia.apellidos}}</dd>
                        <button class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                            v-clipboard:copy="documentoTitulo.user__p__coguia.nombres+' '+documentoTitulo.user__p__coguia.apellidos"
                            v-clipboard:success="onCopy"
                            v-clipboard:error="onError">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dl>
                </dd>
            </dl>
            <template v-if="documentoTitulo.comisiones">
                <dt>Comisión</dt>
                <br>
                <dl class="row" v-if="true">
                    <dt class="col-md-2">Profesor N1</dt>
                    <dd class="col-md-10">
                        <dl class="row">
                            <dt class="col-md-4">Rut</dt>
                            <dd class="col-md-7">{{documentoTitulo.comisiones.user_p1.rut}}</dd>
                            <button class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                                v-clipboard:copy="documentoTitulo.comisiones.user_p1.rut"
                                v-clipboard:success="onCopy"
                                v-clipboard:error="onError">
                                <i class="fas fa-copy"></i>
                            </button>
                        </dl>
                        <dl class="row">
                            <dt class="col-md-4">Nombre completo</dt>
                            <dd class="col-md-7">{{documentoTitulo.comisiones.user_p1.nombres+' '+documentoTitulo.comisiones.user_p1.apellidos}}</dd>
                            <button class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                                v-clipboard:copy="documentoTitulo.comisiones.user_p1.nombres+' '+documentoTitulo.comisiones.user_p1.apellidos"
                                v-clipboard:success="onCopy"
                                v-clipboard:error="onError">
                                <i class="fas fa-copy"></i>
                            </button>
                        </dl>
                    </dd>
                </dl>
                <dl class="row" v-if="documentoTitulo.comisiones.user_p2">
                    <dt class="col-md-2">Profesor N2</dt>
                    <dd class="col-md-10">
                        <dl class="row">
                            <dt class="col-md-4">Rut</dt>
                            <dd class="col-md-7">{{documentoTitulo.comisiones.user_p2.rut}}</dd>
                            <button class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                                v-clipboard:copy="documentoTitulo.comisiones.user_p2.rut"
                                v-clipboard:success="onCopy"
                                v-clipboard:error="onError">
                                <i class="fas fa-copy"></i>
                            </button>
                        </dl>
                        <dl class="row">
                            <dt class="col-md-4">Nombre completo</dt>
                            <dd class="col-md-7">{{documentoTitulo.comisiones.user_p2.nombres+' '+documentoTitulo.comisiones.user_p2.apellidos}}</dd>
                            <button class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                                v-clipboard:copy="documentoTitulo.comisiones.user_p2.nombres+' '+documentoTitulo.comisiones.user_p2.apellidos"
                                v-clipboard:success="onCopy"
                                v-clipboard:error="onError">
                                <i class="fas fa-copy"></i>
                            </button>
                        </dl>
                    </dd>
                </dl>
                <dl class="row" v-if="documentoTitulo.comisiones.p_externo">
                    <dt class="col-md-2">Profesor externo</dt>
                    <dd class="col-md-10">
                        <dl class="row">
                            <dt class="col-md-4">Nombre completo</dt>
                            <dd class="col-md-7">{{documentoTitulo.comisiones.p_externo}}</dd>
                            <button class="col-md-1 btn btn-secondary" title="Copiar al portapapeles"
                                v-clipboard:copy="documentoTitulo.comisiones.p_externo"
                                v-clipboard:success="onCopy"
                                v-clipboard:error="onError">
                                <i class="fas fa-copy"></i>
                            </button>
                        </dl>
                    </dd>
                </dl>
            </template>
          </div>
          <div class="swal2-actions">
            <button type="button" class="swal2-cancel swal2-styled" style="display: inline-block; background-color: #6c757d; --darkreader-inline-bgcolor:#a61c1c;" @click.prevent="showModal=false">Cerrar</button>
          </div>
        </div>
      </div>
    </template>

    </div>
</template>

<script>
import globVar from '../../../services/globVar';
import globFunct from '../../../services/globFunct';
export default {
  data(){
    return{
      globVar: new globVar(),
      globFunct: new globFunct(),
      documentoTitulo: {},
      registro: [],
      fullscreenLoading: false,
      showModal: false,
      selectUser: {},
      terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario'))
    }
  },
  computed: {
  },
  mounted(){
    EventBus.$emit('navegar', 'Registro DARA');
    EventBus.$on('refresh', x => {this.init()});
    this.init();
  },
  beforeDestroy(){
    EventBus.$off('refresh');
  },
  methods:{
    init(){
        this.getListarTesis();
    },
    getListarTesis () {
        const url = '/alumno/getTesisByIdExtendida';
        axios.get(url, {
            params: {
                'id' : this.$route.params.id
            }
        }).then (response => {
            this.documentoTitulo = response.data;
            console.log(this.documentoTitulo);
            this.fullscreenLoading = false;
        })
    },
    guardarDatos() {
        const url = '/secretaria/setRegistroAlumnoDara';
        axios.post(url, {fit_user: this.documentoTitulo.fit__user}
        ).then(response => {
            if (response.status == 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'Datos guardados',
                    showConfirmButton: true,
                    timer: 1500
                }).then(x=>{
                    this.$router.push('/actadefensa');
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Error al guardar los datos',
                    showConfirmButton: true,
                    timer: 1500
                });
            }
        })
    },
    redirectTo(route, objectId, newTab) {
        if (route.search("http") !== 0) {
            if (!newTab) {
                if (objectId) this.$router.push({name: route, params: objectId});
                else this.$router.push({name: route});
            } else {
                const ruteData = this.$router.resolve({name: route, params: objectId});
                window.open(ruteData.href, '_blank');
            }
        } else window.open(route, '_blank');
    },
    mostrarModal(item) {
        this.selectUser = item;
        this.showModal = !this.showModal;
    },
    onCopy() {
        console.log('copiado al portapapeles');
    },
    onError() {
        console.log('error');
    }
  }//cierre de methods
}
</script>

<style>
.boton{
    border:0px !important;
    width: 38px !important;
    height:38px !important;
   }
</style>
