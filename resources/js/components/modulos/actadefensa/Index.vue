<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Criterios de búsqueda</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Nombres o apellido</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" v-model="fillBsqAlumno.cNombre" @keyup.enter="getListarAlumnos">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Rut</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" v-model="fillBsqAlumno.nRut" @keyup.enter="getListarAlumnos">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Estado</label>
                    <div class="col-md-9">
                        <el-select v-model="fillBsqAlumno.nEstadoAlumno"
                        placeholder="Selecciona estado"
                        clearable
                        @change="getListarAlumnos">
                        <el-option
                            v-for="item in listEstadosAlumno"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                        </el-option>
                        </el-select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Estado Acta</label>
                    <div class="col-md-9">
                        <el-select v-model="estadoActa"
                        @change="getListarAlumnos"
                        >
                        <el-option
                            v-for="item in listEstadosActa"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                        </el-option>
                        </el-select>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="getListarAlumnos" v-loading.fullscreen.lock="fullscreenLoading"
                  >{{globVar.btnSearch}}</button>
                <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">{{globVar.btnClear}}</button>
              </div>
            </div>
          </div>
        </div>

        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Bandeja de resultados</h3>
          </div>
          <div class="card-body table table-responsive">
            <template v-if="listarAlumnosPaginated.length">

              <table class ="table table-hover table-head-fixed text-nowrap projects">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Rut</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listarAlumnosPaginated" :key="index">
                    <td> <!-- itera mostrando la cantidad total de estudiantes -->
                        <div v-for="(itemUser, index) in item.alumnos" :key="index">
                            <a v-text="itemUser.nombres + ' ' + itemUser.apellidos"></a>
                        </div>
                    </td>
                    <td> <!-- itera mostrando la cantidad total de estudiantes -->
                        <div v-for="(itemUser, index) in item.alumnos" :key="index">
                            <a v-text="itemUser.rut"></a>
                        </div>
                    </td>
                    <td>
                        {{globFunct.mergedStates(item).resultado}}
                    </td>
                    <td>
                      <button class="btn boton" title="Registro de fecha de defensas" :disabled="!item.path"
                      :class="(item.fecha_defensa && item.path) ? 'btn-primary': 'btn-warning'"
                      @click.prevent="mostrarModal(item)">
                          <i class="fas fa-calendar-day"></i>
                      </button>
                      <router-link title="Subir nota de documento final"  class="btn btn-success boton" :to="{name:'actadefensa.subirnota', params:{id: item.id}}">
                        <i class="fas fa-graduation-cap"></i>
                      </router-link>
                      <!-- <a v-if="item.path" class="btn btn-warning boton" :href="item.path" @click.prevent="downloadItem(item.path)"><i class="fas fa-file-download"> </i></a> -->
                      <router-link :title="'Subir acta de defensa de '+terminoTitulo" class="btn boton" :class="{ 'btn-success' : item.path, 'btn-warning' : !item.path}" :to="{name:'actadefensa.subiracta', params:{id: item.id}}">
                        <i class="fas fa-file-upload"></i>
                      </router-link>
                      <button class="btn boton" title="Registro DARA" :class="(verificarRegistroDara(item)) ? 'btn-primary': 'btn-warning'"
                      @click.prevent="redirectTo('actadefensa.registroDocumento', {id: item.id}, false)">
                          <i class="fas fa-list"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </template>
            <template v-else>
              <div class="callout callout-info">
                <h5> No se han encontrado resultados...</h5>
              </div>
            </template>
          </div>
          <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
              <li class="page-item" v-if="pageNumber > 0">
                <a href="#" class="page-link" @click.prevent="prevPage">Ant</a>
              </li>
              <li class="page-item" v-for="(page, index) in pagesList" :key="index"
                :class="[page == pageNumber ? 'active' : '']"
                :style="(page < pageNumber - 2 || page > pageNumber + 2) ? 'display: none' : ''">
                <a href="#" class=page-link @click.prevent="selectPage(page)"> {{page+1}}</a>
              </li>
              <li class="page-item" v-if="pageNumber < pageCount -1">
                <a href="#" class="page-link" @click.prevent="nextPage">Post</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <template v-if="showModal">
      <div class="swal2-container swal2-center swal2-backdrop-show" style="overflow-y: auto;" @mousedown.prevent="showModal=false">
        <div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-icon-warning swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: flex; z-index: 2; width: 70% !important; min-width: 360px !important; bottom: 15%;" v-on:mousedown.stop>
          <div class="swal2-header">
            <h2 class="swal2-title" id="swal2-title" style="display: flex;">Registrar fecha de defensa de actas</h2>
            <button type="button" class="swal2-close" aria-label="Close this dialog" style="display: none;">×</button>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Fecha de defensa</label>
                    <el-date-picker
                        class="col-md-9"
                        v-model="fechaDefensa"
                        type="datetime"
                        size="large"
                        default-time="08:00:00"
                        placeholder="Selecionar fecha y hora de defensa de tesis">
                    </el-date-picker>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Sala de defensa</label>
                    <el-input class="col-md-9" placeholder="Escriba la sala donde se realizará la defensa" v-model="salaDefensa">
                    </el-input>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="swal2-content text-left">
          </div>
          <div class="swal2-actions">
            <button class="swal2-confirm swal2-styled" @click.prevent="setRegistrarDefensa" v-loading.fullscreen.lock="fullscreenLoading">{{globVar.btnSave}}</button>
            <button type="button" class="swal2-cancel swal2-styled" style="display: inline-block; background-color: #6c757d; --darkreader-inline-bgcolor:#a61c1c;" @click.prevent="showModal=false">{{globVar.btnClose}}</button>
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
      fillBsqAlumno:{
        cNombre: '',
        nRut: '',
        nEstadoAlumno:''
      },
      fullscreenLoading: false,
      listAlumnos:[],
      listEscuelas:[],
      listEstados: [
        {value: 'A', label: 'Activo'},
        {value: 'I', label: 'Inactivo'}
      ],
      listEstadosAlumno: [
        {value: 'A', label: 'Aprobada'},
        {value: 'R', label: 'Reprobada'},
        {value: 'D', label: 'En desarrollo'}
      ],
      listEstadosActa: [
        {value: 2, label: 'Todas'},
        {value: 1, label: 'Con Acta'},
        {value: 0, label: 'Sin Acta'}
      ],
      terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      showModal: false,
      pageNumber: 0,
      perPage: 10,
      estadoActa: 2,
      nivelAcceso: 1,
      fechaDefensa: '',
      salaDefensa: '',
      tesisSelected: ''
    }
  },
  computed: {
    pageCount(){
      //obtener el numero de paginas
      let a = this.listAlumnos.length,
          b = this.perPage;
      return Math.ceil(a / b);
    },
    listarAlumnosPaginated(){
      //
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listAlumnos.slice(inicio, fin);
    },
    pagesList(){
      let a = this.listAlumnos.length,
          b = this.perPage;
      let pageCount = Math.ceil(a / b);
      let count = 0,
        pagesArray = [];
      while (count < pageCount){
        pagesArray.push(count);
        count++;
      }
      return pagesArray;
    }
  },
  mounted(){
    EventBus.$emit('navegar', 'Actas de defensa y notas');
    EventBus.$on('refresh', x => {this.init()});
    this.init();
    },
  beforeDestroy(){
    EventBus.$off('refresh');
  },
  methods:{
    init(){
      this.getListarAlumnos();
    },
    downloadItem (url) {
    axios.get(url, { responseType: 'blob' })
      .then(response => {
        const blob = new Blob([response.data], { type: 'application/pdf' });
        var link = URL.createObjectURL(blob);
        window.open(link);
      }).catch(console.error);
    },
    limpiarCriteriosBsq(){
      this.fillBsqAlumno.cNombre = '';
      this.fillBsqAlumno.nRut = '';
      this.fillBsqAlumno.nIdEscuela = '';
      this.fillBsqAlumno.nEstadoAlumno='';
      this.estadoActa = 2;
    },
    limpiarBandejaUsuarios(){
      this.listAlumnos = [];
    },
    getListarAlumnos(){
      this.fullscreenLoading = true;
      var url = '/secretaria/getListarActas';
      if (this.listRolPermisosByUsuario.includes('fid.acceso.total')) {
        this.nivelAcceso = 0;
      }
      axios.get(url, {
        params: {
          'cNombre'       : this.fillBsqAlumno.cNombre,
          'nRut'          : this.fillBsqAlumno.nRut,
          'nEstadoAlumno' : this.fillBsqAlumno.nEstadoAlumno,
          'nEstadoActa'   : this.estadoActa,
          'nivelAcceso'   : this.nivelAcceso
        }
      }).then(response => {
          this.inicializarPaginacion();
          this.listAlumnos = response.data;
          this.fullscreenLoading = false;
      })
    },
    nextPage(){
      this.pageNumber++;
    },
    prevPage(){
      this.pageNumber--;
    },
    selectPage(page){
      this.pageNumber = page;
    },
    inicializarPaginacion(){
      this.pageNumber = 0;
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
    verificarRegistroDara(data) {
        var response = true;
        data.fit__user.forEach(fitUser => {
            if (fitUser.reg_doc == 0) response = false;
        });
        return response;
    },
    mostrarModal(item) {
        this.fechaDefensa   = '';
        this.salaDefensa    = '';
        if (item.fecha_defensa) {
            this.fechaDefensa   = item.fecha_defensa.fecha;
            this.salaDefensa    = item.fecha_defensa.sala;
        }
        this.tesisSelected = item.id;
        this.showModal = !this.showModal;
    },
    setRegistrarDefensa() {
        this.showModal = false;
        if (this.fechaDefensa && this.salaDefensa) {
            this.fullscreenLoading = true;
            var url = '/secretaria/setRegistrarDefensaActa';
            var userTimezoneOffset = this.fechaDefensa.getTimezoneOffset() * 60000;
            this.fechaDefensa = new Date(this.fechaDefensa.getTime() - userTimezoneOffset);
            axios.post(url, {
                'nIdFit'        : this.tesisSelected,
                'fechaDefensa'  : this.fechaDefensa,
                'salaDefensa'   : this.salaDefensa
            }).then(response => {
                this.getListarAlumnos();
                Swal.fire({
                    icon: 'success',
                    title: 'La fecha y sala ha sido guardada.',
                    showConfirmButton: true,
                    timer: 1500
                });
                this.fullscreenLoading = false;
            })
        } else {
          setTimeout(function(){
            Swal.fire({
              icon: 'warning',
              title: 'Todos los datos son obligatorios.',
              showConfirmButton: true,
              timer: 1500
            });
          }, 50);
        }
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
