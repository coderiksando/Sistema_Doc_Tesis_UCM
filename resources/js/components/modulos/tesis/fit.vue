<template>
  <div class="card">
    <template v-if="listRolPermisosByUsuario.includes('tesis.crear')">
    <div class="card-header">
      <div class="card-tools">
        <template v-if="listTesis.length === 0">
          <router-link class="btn btn-info bnt-sm" :to="'/tesis/crear'">
            <i class="fas fa-plus-square"></i> Ingresar formulario de inscripción
          </router-link>
        </template>
        <template v-else>
          <router-link class="btn btn-danger bnt-sm link-disabled" :to="''">
          Usted ya ingresó un formulario
          </router-link>
        </template>
      </div>
    </div>
    </template>
    <div class="card-body" style="min-height: 70vh !important">
      <div class="container-fluid">

        <div class="card card-info" v-if="!listRolPermisosByUsuario.includes('EsAlumno')">
          <div class="card-header">
            <h3 class="card-title">Criterios de búsqueda</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-4 col-form-label">Alumno</label>
                    <div class="col-md-4">
                        <input placeholder="Nombre" type="text" class="form-control" v-model="fillBsqTesis.cNombre" @keyup.enter="getListarTesis">
                    </div>
                    <div class="col-md-4">
                        <input placeholder="Apellido" type="text" class="form-control" v-model="fillBsqTesis.cApellido" @keyup.enter="getListarTesis">
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-4 col-form-label">Estado de aprobación</label>
                    <div class="col-md-8">
                        <el-select v-model="fillBsqTesis.cEstado"
                        placeholder="Seleccione un estado">
                          <el-option
                            v-for="item in listEstadosTesis"
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
                    <label class="col-md-4 col-form-label">Fecha</label>
                    <div class="col-md-8">
                      <el-date-picker
                        v-model="fillBsqTesis.dfecha"
                        type="year"
                        range-separator="/"
                        placeholder="Año"
                        value-format="yyyy-MM-dd">
                      </el-date-picker>
                    </div>
                  </div>
                </div>
              </div>
            </form>

          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="getListarTesis"
                  >{{globVar.btnSearch}}</button>
                <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">{{globVar.btnClear}}</button>
              </div>
            </div>
          </div>
        </div>

        <div class="card card-info">
          <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title">Bandeja de resultados</h3>
                </div>
                <div class="col-md-6" style="text-align: right;">
                    <button class="btn btn-secondary" @click.prevent="mostrarModalAyuda">Ayuda
                        <i class="fas fa-question-circle" ></i>
                    </button>
                </div>
            </div>
          </div>
          <div class="card-body table table-responsive" v-loading="fullscreenLoading">
            <template v-if="listTesis.length">
              <table class ="table table-hover table-head-fixed text-nowrap projects">
                <thead>
                  <tr>
                    <th>Alumno(s)</th>
                    <th>Estado de inscripción</th>
                    <th>Estado de aprobación</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listarTesisPaginated" :key="index">
                    <td> <!-- itera mostrando la cantidad total de estudiantes -->
                        <div v-for="(itemUser, index) in item.listUsers" :key="index">
                            <div v-text="itemUser.nombres + ' ' + itemUser.apellidos"></div>
                        </div>
                    </td>

                    <!-- <td v-text="item.nombre_int1"></td> -->
                    <td>
                      <template v-if="item.aprobado_pg == 'P'">
                        Pendiente
                      </template>
                      <template v-else-if="item.aprobado_pg == 'A'">
                        Aprobado
                      </template>
                      <template v-else-if="item.aprobado_pg == 'V'">
                        Verificado
                      </template>
                      <template v-else>
                        Rechazado
                      </template>
                    </td>
                    <td>
                      <template v-if="item.estado == 'D'">
                        En desarrollo
                      </template>
                      <template v-else-if="item.estado == 'A'">
                        Aprobada
                      </template>
                      <template v-else>
                        Reprobada
                      </template>
                    </td>
                    <td>
                      <router-link :title="'Ver '+ terminoTitulo" class="btn boton btn-primary" :to="{name:'tesis.ver', params:{id: item.id}}">
                        <i class="fas fa-eye"></i>
                      </router-link>
                      <template v-if="(item.aprobado_pg == 'A' || item.aprobado_pg == 'V') && listRolPermisosByUsuario.includes('tesis.subirconstancia')">
                        <router-link title="Subir constancia de examen" class="btn btn-success boton" :to="{name:'tesis.subirconstancia'}">
                          <i class="fas fa-file-upload"></i>
                        </router-link>
                      </template>
                      <template v-if="item.aprobado_pg == 'A' || item.aprobado_pg == 'V'">
                        <button title="Generar documento PDF" class="btn boton btn-warning" @click.prevent="setGenerarDocumento(item.id)">
                          <i class="fas fa-file-download"></i>
                        </button>
                      </template>
                      <template v-if="item.constancia && rolActivo != 'Alumno'">
                        <a title="Descargar constancia de examen" class="btn boton btn-info" :href="item.constancia.path" target="_blank">
                          <i class="fas fa-file-download"></i>
                        </a>
                      </template>
                      <template v-if="item.comisiones && rolActivo == 'Alumno'">
                        <router-link title="Ver revisiones de comisión" class="btn boton btn-primary" :to="'tesis/revisiones'">
                          <i class="fa fa-list-alt"></i>
                        </router-link>
                      </template>

                      <template v-if="(item.aprobado_pg == 'A' && (rolActivo == 'Director' || rolActivo == 'Coordinador')) || item.aprobado_pg == 'P' || item.aprobado_pg == 'R'">
                        <template v-if="item.aprobado_pg == 'R'">
                            <button title="Ver razon de rechazo" class="btn boton btn-danger" @click.prevent="verRazonRechazo(item.motivo_pg)">
                            <i class="fas fa-exclamation-circle"></i>
                            </button>
                            <router-link :title="'Editar '+terminoTitulo" class="btn boton btn-info" :to="{name:'tesis.editar', params:{id: item.id}}">
                            <i class="fas fa-pencil-alt"></i>
                            </router-link>
                        </template>

                        <template  v-if="listRolPermisosByUsuario.includes('tesis.aprobar')">
                            <button :title="'Aprobar '+terminoTitulo" class="btn boton btn-success" @click.prevent="setCambiarEstadoFIT(1, item.id)">
                              <i class="fas fa-check"></i>
                            </button>
                            <button :title="'Rechazar '+terminoTitulo" class="btn boton btn-danger" @click.prevent="modalRechazo(item)">
                              <i class="fas fa-times"></i>
                            </button>
                        </template>
                      </template>
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
                :class="[page == pageNumber ? 'active' : '']">
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

    <div class="modal fade" :class="{ show: modalAyuda }" :style="modalAyuda ? mostrarModal : ocultarModal">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Ventana de ayuda de íconos</h5>
            <button class="close" @click="mostrarModalAyuda"></button>
            </div>
            <div class="modal-body">
            <table class ="table table-hover table-head-fixed t-fixed projects ">
                <thead>
                    <tr>
                        <th class="col-md-1">Ícono</th>
                        <th class="col-md-2">Nombre</th>
                        <th class="col-md-9">Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i class="fas fa-plus-square"></i></td>
                        <td>Agregar</td>
                        <td>Módulo de registro de datos de {{terminoTitulo}}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-eye"></i></td>
                        <td>Ver</td>
                        <td>Visión protegida de los datos ingresados en {{terminoTitulo}}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-file-download"></i></td>
                        <td>Descarga</td>
                        <td>Descarga de los datos inscritos en la plataforma</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-pencil-alt"></i></td>
                        <td>Edición</td>
                        <td>Visión y cambios de datos de {{terminoTitulo}}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-exclamation-circle"></i></td>
                        <td>Ver motivo</td>
                        <td>Se muestra en pantalla el motivo de un posible rechazo de su {{terminoTitulo}}</td>
                    </tr>
                    <template v-if="listRolPermisosByUsuario.includes('tesis.aprobar')">
                        <tr>
                            <td><i class="fas fa-check"></i></td>
                            <td>Aprobar</td>
                            <td>Aprobación de los datos inscritos por el alumno (envío de correo)</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-times"></i></td>
                            <td>Rechazar</td>
                            <td>Rechazo de los datos inscritos por el alumno (envío de correo)</td>
                        </tr>
                    </template>
                </tbody>
                </table>
            </div>
            <div class="modal-footer">
            <button class="btn btn-secondary" @click="mostrarModalAyuda">{{globVar.btnClose}}</button>
            </div>
        </div>
        </div>
    </div>

    <template v-if="mostrarModalRechazo">
      <div class="swal2-container swal2-center swal2-backdrop-show" style="overflow-y: auto;" @click.prevent="dismissModal()">
        <div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-icon-warning swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: flex; z-index: 2;" v-on:click.stop>
          <div class="swal2-header">
            <div class="swal2-icon swal2-warning swal2-icon-show" style="display: flex;">
              <div class="swal2-icon-content">!</div>
            </div>
            <h2 class="swal2-title" id="swal2-title" style="display: flex;">¿Escriba el motivo de su rechazo (opcional)?</h2>
            <button type="button" class="swal2-close" aria-label="Close this dialog" style="display: none;">×</button>
          </div>
          <div class="swal2-content">
            <div id="swal2-content" class="swal2-html-container" style="display: none;"></div>
            <textarea v-model="motivo" class="swal2-textarea" style="display: flex;"></textarea>
            <div class="swal2-validation-message" id="swal2-validation-message"></div>
          </div>
          <div class="swal2-actions">
            <button type="button" class="swal2-confirm swal2-styled" style="display: inline-block; background-color: rgb(48, 133, 214); border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214); --darkreader-inline-bgcolor:#2166a7; --darkreader-inline-border-left:#1d5a93; --darkreader-inline-border-right:#1d5a93;"  @click.prevent="setCambiarEstadoFITRechazo">Si, Rechazar</button>
            <button type="button" class="swal2-cancel swal2-styled" style="display: inline-block; background-color: rgb(221, 51, 51); --darkreader-inline-bgcolor:#a61c1c;" @click.prevent="dismissModal()">Cancelar</button>
          </div>
        </div>
      </div>
    </template>
</div>
</template>

<script>
import globVar from '../../../services/globVar';
export default {
    props: ['usuario'],
  data(){
    return{
      globVar: new globVar(),
      fillBsqTesis:{
        cNombre       : '',
        cApellido     : '',
        cEstadoPg     : '',
        cEstado       : '',
        dfecha        : ''
      },
      fillVerFIT:{
        cNombre: '',
        cSlug: ''
      },
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      listRolByUser: JSON.parse(localStorage.getItem('rolUser')),
      terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
      terminoTituloExtendido: JSON.parse(localStorage.getItem('TerminoDeTituloExtendido')),
      listEstadosFIT: [
        {value: 'V', label: 'Verificado'},
        {value: 'A', label: 'Aprobado'},
        {value: 'P', label: 'Pendiente'},
        {value: 'R', label: 'Rechazado'}
      ],
      listEstadosTesis: [
        {value: 'A', label: 'Aprobada'},
        {value: 'R', label: 'Reprobada'},
        {value: 'D', label: 'En Desarrollo'}
      ],
      listTesis: 1,
      listAllTesis:1,
      listMiTesis:[],
      fullscreenLoading: false,
      pageNumber: 0,
      perPage: 5,
      modalShow: false,
      modalAyuda: false,
      modalOption: 0,
      mostrarModal: {
        display: 'block',
        background: '#0000006b',
      },
      ocultarModal: {
        display: 'none',
      },
      error: 0,
      mensajeError:[],
      rolActivo : JSON.parse(localStorage.getItem('rolActivo')),
      mostrarModalRechazo: false,
      motivo: '',
      idTesis: ''
    }
  },
  computed: {
    pageCount(){
      //obtener el numero de paginas
      let a = this.listTesis.length,
          b = this.perPage;
      return Math.ceil(a / b);
    },
    listarTesisPaginated(){
      //
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listTesis.slice(inicio, fin);
    },
    pagesList(){
      let a = this.listTesis.length,
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
  created(){
    EventBus.$emit('navegar', 'Ingresar/Revisar FID');
    this.getListarTesis();
  },
  methods:{
    setGenerarDocumento(nIdTesis){
      //this.fullscreenLoading = true;
      var config = {
        responseType: 'blob'
      }
      var url = '/administracion/tesis/setGenerarDocumento'
      axios.post(url, {
          'nIdTesis' :nIdTesis
      }, config).then(response => {
          var oMyBlob = new Blob([response.data], {type : 'application/pdf'});
          var url = URL.createObjectURL(oMyBlob);
          window.open(url);
      })
    },
    getListarTesis(){
      this.fullscreenLoading = true;
      var url = '/alumno/getListarTesis';
      axios.get(url, {
        params:{
          'nombre'    :   this.fillBsqTesis.cNombre,
          'apellido'  :   this.fillBsqTesis.cApellido,
          'estadoI'   :   this.fillBsqTesis.cEstadoPg,
          'estado'    :   this.fillBsqTesis.cEstado,
          'fecha'   :    (!this.fillBsqTesis.dfecha) ? '' : this.fillBsqTesis.dfecha,
        }
      }).then(response => {
            this.inicializarPaginacion();
            this.listTesis = response.data;
            this.fullscreenLoading = false;
      })
    },
    getListarAllTesis(){
      this.fullscreenLoading = true;
      var url = '/alumno/getListarAllTesis'
      axios.get(url, {
      }).then(response => {
          this.listAllTesis = response.data;
          this.fullscreenLoading = false;
      })
    },
    limpiarCriteriosBsq(){
      this.fillBsqTesis.cNombre = '';
      this.fillBsqTesis.cApellido = '';
      this.fillBsqTesis.cEstado = '';
      this.fillBsqTesis.cEstadoPg = '';
      this.fillBsqTesis.dfecha = '';

    },
    limpiarBandejaUsuarios(){
      this.listTesis = [];
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

  setCambiarEstadoFIT(op, id){
        Swal.fire({
        title: '¿Está seguro que desea ' + ((op == 1) ? 'aprobar ' : 'rechazar ') + 'el formulario de inscripción?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: ((op == 1) ? 'Si, Aprobar' : 'No, Rechazar'),
        cancelButtonText: 'Cancelar',
        }).then((result) => {
        if (result.value) {
            this.fullscreenLoading = true;
            var url = '/alumno/setCambiarEstadoFIT'
            axios.post(url, {
            'nIdTesis' : id,
            'cEstadoPg'    : (op == 1) ? 'A' : 'R'
            }).then(response => {
                this.fullscreenLoading = false;
                Swal.fire({
                icon: 'success',
                title: 'Se ' + ((op == 1) ? 'aprobó ' : 'rechazó ') +' el formulario de inscripción',
                showConfirmButton: false,
                timer: 1500
                })
                this.getListarTesis();
            })
        }
        })
    },
    setCambiarEstadoFITRechazo () {
      this.mostrarModalRechazo = false;
      var url = '/alumno/setCambiarEstadoFIT'
      axios.post(url, {
          'nIdTesis'  : this.idTesis,
          'cEstadoPg' : 'R',
          'motivo'    : this.motivo
      })
      .then(response => {
          Swal.fire({
          icon: 'success',
          title: 'Se rechazó el formulario de inscripción',
          showConfirmButton: false,
          timer: 1500
          })
          this.getListarTesis();
      })
    },

    mostrarModalAyuda() {
        this.modalAyuda = !this.modalAyuda;
    },
    verRazonRechazo(data) {
        Swal.fire({
            icon: 'warning',
            title: 'Motivo de rechazo',
            padding: 0,
            html: '<div style="white-space: pre-wrap; max-height: 50vh; overflow: auto; padding: 10px">'+data+'</div>'
        })
    },
    modalRechazo(fit){
      this.motivo = fit.motivo_pg;
      this.idTesis = fit.id;
      this.mostrarModalRechazo = true;
    },
    dismissModal(){
      this.listTesis.find(fit => fit.id == this.idTesis).motivo_pg = this.motivo;
      this.mostrarModalRechazo = false;
    },

  }//cierre de methods
}
</script>

<style>
  .scrollTable{
    max-height: 350px !important;
    overflow: auto !important;
  }

  .mine{
    padding: 1.25rem 1.25rem 0 1.25rem !important;
  }
</style>
