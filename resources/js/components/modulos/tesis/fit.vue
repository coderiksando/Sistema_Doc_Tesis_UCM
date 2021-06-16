<template>

  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0 text-dark"><b>Formulario de Inscripción de Tesis (FIT)</b></h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div class="container container-fluid">
      <div class="card">
        <template v-if="listRolPermisosByUsuario.includes('tesis.crear')">
        <div class="card-header">
          <div class="card-tools">
            <template v-if="listTesis.length === 0">
              <router-link class="btn btn-info bnt-sm" :to="'/tesis/crear'">
                <i class="fas fa-plus-square"></i>Ingresar formulario de inscripción
              </router-link>
            </template>
            <template v-else>
              <router-link class="btn btn-info bnt-sm link-disabled" :to="''">
              <i class="fas fa-plus-square"></i>Usted ya ingresó un formulario
              </router-link>
            </template>
          </div>
        </div>
        </template>
        <div class="card-body">
          <div class="container-fluid">
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
              <div class="card-body table table-responsive">
                <template v-if="listTesis.length">

                  <table class ="table table-hover table-head-fixed text-nowrap projects" v-loading.fullscreen.lock="fullscreenLoading">
                    <thead>
                      <tr>
                        <th>Alumno(s)</th>
                        <th>Estado</th>
                        <th>Estado Tesis</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in listTesis" :key="index">
                        <td> <!-- itera mostrando la cantidad total de estudiantes -->
                            <div v-for="(itemUser, index) in item.listUsers" :key="index">
                                <p v-text="itemUser.nombres + ' ' + item.listUsers[0].apellidos"></p>
                            </div>
                        </td>

                        <!-- <td v-text="item.nombre_int1"></td> -->
                        <td>
                          <template v-if="item.aprobado_pg == 'P'">
                            <span class="badge badge-warning" >Pendiente</span>
                          </template>
                          <template v-else-if="item.aprobado_pg == 'A'">
                            <span class="badge badge-success">Aprobado</span>
                          </template>
                          <template v-else-if="item.aprobado_pg == 'V'">
                            <span class="badge badge-success">Verificado</span>
                          </template>
                          <template v-else>
                            <span class="badge badge-danger">Rechazado</span>
                          </template>
                        </td>
                        <td>
                          <template v-if="item.estado == 'D'">
                            <span class="badge badge-warning" >En desarrollo</span>
                          </template>
                          <template v-else-if="item.estado == 'A'">
                            <span class="badge badge-success" >Aprobada</span>
                          </template>
                          <template v-else>
                            <span class="badge badge-danger" >Reprobada</span>
                          </template>
                        </td>
                        <td>
                          <router-link class="btn boton btn-primary" :to="{name:'tesis.ver', params:{id: item.id}}">
                            <i class="fas fa-folder"></i>
                          </router-link>
                          <template v-if="item.aprobado_pg == 'A' || item.aprobado_pg == 'V'">
                            <button class="btn boton btn-warning" @click.prevent="setGenerarDocumento(item.id)">
                              <i class="fas fa-file-download"></i>
                            </button>
                          </template>
                          <template v-if="(item.aprobado_pg == 'A' && (rolActivo == 'Director' || rolActivo == 'Coordinador')) || item.aprobado_pg == 'P' || item.aprobado_pg == 'R'">
                            <template v-if="item.aprobado_pg == 'R'">
                                <button class="btn boton btn-danger" @click.prevent="verRazonRechazo(item.motivo_pg)">
                                <i class="fas fa-eye"></i>
                                </button>
                                <router-link class="btn boton btn-info" :to="{name:'tesis.editar', params:{id: item.id}}">
                                <i class="fas fa-pencil-alt"></i>
                                </router-link>
                            </template>

                            <template  v-if="listRolPermisosByUsuario.includes('tesis.aprobar')">
                                <button class="btn boton btn-success" @click.prevent="setCambiarEstadoFIT(1, item.id)">
                                  <i class="fas fa-check"></i>
                                </button>
                                <button class="btn boton btn-danger" @click.prevent="setCambiarEstadoFITRechazo(2, item.id)">
                                  <i class="fas fa-trash"></i>
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

        <!-- SEGUNDOD BODY SI ES ADMIN-->
        <template v-if="listRolByUser[0].nIdRol === 6">
          <div class="content-header mine">
            <div class="container-fluid">
              <h1 class="m-0 text-dark">Otros FID</h1>
              <h5>Formularios de alumnos con otros profesores</h5>
          </div><!-- /.container-fluid -->
        </div>
                <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Bandeja de resultados</h3>
              </div>
              <div class="card-body table table-responsive">
                <template v-if="listAllTesis.length">

                  <table class ="table table-hover table-head-fixed t-fixed projects ">
                    <thead>
                      <tr>
                        <th>Alumno</th>
                        <th>Estado FIT</th>
                        <th>Estado Tesis</th>
                        <th>Acciones </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in listAllTesis" :key="index">


                        <td v-text="item.nombre_int1"></td>
                        <td>
                          <template v-if="item.aprobado_pg == 'P'">
                            <span class="badge badge-warning" >Pendiente</span>
                          </template>
                          <template v-else-if="item.aprobado_pg == 'A'">
                            <span class="badge badge-success">Aprobado</span>
                          </template>
                          <template v-else>
                            <span class="badge badge-danger">Rechazado</span>
                          </template>
                        </td>
                        <td>
                          <template v-if="item.estado == 'D'">
                            <span class="badge badge-warning" >En desarrollo</span>
                          </template>
                          <template v-else-if="item.estado == 'A'">
                            <span class="badge badge-success" >Aprobada</span>
                          </template>
                          <template v-else>
                            <span class="badge badge-danger" >Reprobada</span>
                          </template>
                        </td>
                        <td>
                          <router-link class="btn btn-flat btn-primary btn-sm" :to="{name:'tesis.ver', params:{id: item.id}}">
                            <i class="fas fa-folder"></i> Ver
                          </router-link>
                          <template v-if="item.aprobado_pg == 'A'">
                            <button class="btn btn-flat btn-light btn-sm" @click.prevent="setGenerarDocumento(item.id)">
                              <i class="fas fa-pencil-alt"></i> Ver PDF
                            </button>
                          </template>
                          <template v-if="item.aprobado_pg == 'P' || item.aprobado_pg == 'R'">
                            <router-link class="btn btn-flat btn-info btn-sm" :to="{name:'tesis.editar', params:{id: item.id}}">
                              <i class="fas fa-pencil-alt"></i> Editar
                            </router-link>
                            <template  v-if="listRolPermisosByUsuario.includes('tesis.aprobar')">
                                <button class="btn btn-flat btn-success btn-sm" @click.prevent="setCambiarEstadoFIT(1, item.id)">
                                  <i class="fas fa-check"></i>Aprobar
                                </button>
                                <button class="btn btn-flat btn-danger btn-sm" @click.prevent="setCambiarEstadoFITRechazo(2, item.id)">
                                  <i class="fas fa-trash"></i>Rechazar
                                </button>
                            </template>
                          </template>
                        </td>
                      </tr>
                    </tbody>
                  </table>
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
                </template>
                <template v-else>
                  <div class="callout callout-info">
                    <h5> No se han encontrado resultados...</h5>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
        </template>
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
                        <th class="col-md-9">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i class="fas fa-plus-square"></i></td>
                        <td>Agregar</td>
                        <td>Módulo de registro de datos de FIT</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-folder"></i></td>
                        <td>Ver</td>
                        <td>Visión protegida de los datos ingresados en FIT</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-file-download"></i></td>
                        <td>Descarga</td>
                        <td>Descarga de los datos inscritos en la plataforma</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-pencil-alt"></i></td>
                        <td>Edición</td>
                        <td>Visión y cambios de datos de FIT</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-eye"></i></td>
                        <td>Ver motivo</td>
                        <td>Se muestra en pantalla el motivo de un posible rechazo de su FIT</td>
                    </tr>
                    <template v-if="listRolPermisosByUsuario.includes('tesis.aprobar')">
                        <tr>
                            <td><i class="fas fa-check"></i></td>
                            <td>Aprobar</td>
                            <td>Aprobación de los datos inscritos por el alumno (envío de correo)</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-trash"></i></td>
                            <td>Rechazar</td>
                            <td>Rechazo de los datos inscritos por el alumno (envío de correo)</td>
                        </tr>
                    </template>
                </tbody>
                </table>
            </div>
            <div class="modal-footer">
            <button class="btn btn-secondary" @click="mostrarModalAyuda">Cerrar</button>
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
      fillBsqTesis:{
        nIdTesis: '',
        cTitulo: '',
        nIdPg: '',
        cNombreI1: '',
        cEstadoPg: '',
        cEstadoD: '',
        cEstadoTesis: ''
      },
      fillVerFIT:{
        cNombre: '',
        cSlug: ''
      },
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      listRolByUser: JSON.parse(localStorage.getItem('rolUser')),
      listEstadosFIT: [
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
      rolActivo : JSON.parse(localStorage.getItem('rolActivo'))
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
    this.getListarTesis();
    this.getListarAllTesis();
    //this.getListarMiTesis();
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
      var url = '/alumno/getListarTesis'
      axios.get(url, {
      }).then(response => {
            this.inicializarPaginacion();
            this.listTesis = response.data;
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
    getListarMiTesis(){
      this.fullscreenLoading = true;
      var url = '/alumno/getListarMiTesis'
      axios.get(url, {
      }).then(response => {
          this.listMiTesis = response.data;
          this.fullscreenLoading = false;
      })
    },
    limpiarCriteriosBsq(){
      this.fillBsqTesis.cNombre = '';
      this.fillBsqTesis.cSlug = '';
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
        title: 'Estas seguro? ' + ((op == 1) ? 'Aprobar ' : 'Rechazar ') + '  El formulario de inscripcion',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: ((op == 1) ? 'Si, Aprobar' : 'Si, Rechazar'),
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
                title: 'Se ' + ((op == 1) ? 'Aprobó ' : 'Rechazó ') +' El formulario de inscripción',
                showConfirmButton: false,
                timer: 1500
                })
                this.getListarTesis();
            })
        }
        })
    },
    setCambiarEstadoFITRechazo (op, id) {
        Swal.fire({
        title: 'Escriba el motivo de su rechazo (opcional)',
        icon: 'warning',
        input: 'textarea',
        inputAttributes: {autocapitalize: 'off'},
        showCancelButton: true,
        confirmButtonText: 'Enviar rechazo',
        cancelButtonText: 'Cancelar rechazo',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        }).then((response) => {
        if (response.value) {
            this.fullscreenLoading = false;
            var url = '/alumno/setCambiarEstadoFIT'
            axios.post(url, {
                'nIdTesis'  : id,
                'cEstadoPg' : (op == 1) ? 'A' : 'R',
                'motivo'    : response.value
            })
            .then(response => {
                Swal.fire({
                icon: 'success',
                title: 'Se ' + ((op == 1) ? 'Aprobó ' : 'Rechazó ') +' El formulario de inscripción',
                showConfirmButton: false,
                timer: 1500
                })
                this.getListarTesis();
            })
        }
        })
    },
    mostrarModalAyuda() {
        this.modalAyuda = !this.modalAyuda;
    },
    verRazonRechazo(data) {
        Swal.fire({
            icon: 'warning',
            title: 'Motivo de rechazo',
            text: data,
        })
    }

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
