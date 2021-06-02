<template>
  <div>

    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><b>Formulario de inscripción de tesis</b></h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div class="container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info bnt-sm" :to="'/tesis'">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Formulario  inscripcion de tesis</h3>
                        </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Profesor Guia</label>
                                            <div class="col-md-9">
                                                <input type="text" readonly class="form-control" v-model="fillVerFIT.cProfesorguia" @keyup.enter="setRegistrarTesis">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Profesor Co-guía</label>
                                            <div class="col-md-9">
                                                <input type="text" readonly class="form-control" v-model="fillVerFIT.cProfesorCoGuia" @keyup.enter="setRegistrarTesis">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Tipo de trabajo</label>
                                            <div class="col-md-9">
                                                <input type="text" readonly class="form-control" v-model="fillVerFIT.cTipo" @keyup.enter="setRegistrarTesis">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Vinculacion</label>
                                            <div class="col-md-9">
                                                <input type="text" readonly class="form-control" v-model="fillVerFIT.nIdVinculacion" @keyup.enter="setRegistrarTesis">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Titulo</label>
                                            <div class="col-md-9">
                                                <textarea class="form-control" readonly v-model="fillVerFIT.cTitulo" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Contribucion</label>
                                        <div class="col-md-9">
                                        <textarea class="form-control" readonly v-model="fillVerFIT.cContribucion" rows="3"></textarea>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Objetivo</label>
                                        <div class="col-md-9">
                                        <textarea class="form-control" readonly v-model="fillVerFIT.cObjetivo" rows="3"></textarea>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Descripcion</label>
                                        <div class="col-md-9">
                                        <textarea class="form-control" readonly v-model="fillVerFIT.cDescripcion" rows="3"></textarea>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h3><b v-text="'Tabla de integrantes'"></b></h3>
                                    </div>
                                    <div class="col-md-6">

                                    </div>
                                    <table class ="table table-hover table-head-fixed text-nowrap projects ">
                                        <thead>
                                            <tr>
                                                <th>Nombre integrante</th>
                                                <th>Rut</th>
                                                <th>Correo electrónico</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(item, index) in fillVerFIT.users" :key="index">
                                            <td>{{item.user.nombres + ' ' + item.user.apellidos}}</td>
                                            <td>{{item.user.rut}}</td>
                                            <td>{{item.user.email}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                  </div>
              </div>
          </div>
      </div>

    <div class="modal fade" :class="{ show: modalShow }" :style="modalShow ? mostrarModal : ocultarModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Sistema de tesis UCM</h5>
            <button class="close" @click="abrirModal"></button>
          </div>
          <div class="modal-body">
            <div class="callout callout-danger" style="padding: 5px" v-for="(item, index) in mensajeError" :key="index" v-text="item"></div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="abrirModal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      fillVerFIT:{
        nIdTesis: this.$attrs.id,
        cTitulo: '',
        nIdPg: '',
        nIdCoPg: '',
        nIdVinculacion: '',
        cTipo: '',
        cObjetivo: '',
        cContribucion: '',
        cDescripcion: '',
        users: []
      },
      listTipo: [
        {value: 'Tesis', label: 'Tesis'},
        {value: 'Memoria', label: 'Memoria'}
      ],

      listEscuelas:[],
      listVinculacion:[],
      fullscreenLoading: false,
      modalShow: false,
      mostrarModal: {
        display: 'block',
        background: '#0000006b',
      },
      ocultarModal: {
        display: 'none',
      },
      error: 0,
      mensajeError:[]
    }
  },
  computed: {
  },
   mounted(){
    this.getTesisById();
    this.getListarVinculacion();
  },
  methods:{
    getListarVinculacion(){
      this.fullscreenLoading = true;
      var url = '/administracion/vinculacion/getListarVinculacion'
      axios.get(url, {
      }).then(response => {
          //this.inicializarPaginacion();
          this.listVinculacion = response.data;
          this.fullscreenLoading = false;
      })
    },

    limpiarCriterios(){
      this.fillVerFIT.cNombre = '';
      this.fillVerFIT.nIdEscuela = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
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
    getTesisById(){
        this.fullscreenLoading = true;
        var url = '/alumno/getListarTesisView'
        axios.get(url, {
        params: {
          'nIdTesis' : this.fillVerFIT.nIdTesis
        }
      }).then(response => {
          this.getUsuarioVer(response.data);
          this.fullscreenLoading = false;
      })
    },
    getUsuarioVer(data){
          this.fillVerFIT.cTitulo = data.titulo;
          this.fillVerFIT.cProfesorguia = data.user__p__guia.nombres + ' ' + data.user__p__guia.apellidos;
          this.fillVerFIT.cProfesorCoGuia = data.user__p__coguia.nombres + ' ' + data.user__p__coguia.apellidos;
          this.fillVerFIT.nIdVinculacion = data.vinculaciones.nombre;
          this.fillVerFIT.cTipo = data.tipo;
          this.fillVerFIT.cObjetivo = data.objetivo;
          this.fillVerFIT.cContribucion = data.contribucion;
          this.fillVerFIT.cDescripcion = data.descripcion;
          this.fillVerFIT.users = data.fit__user;
    },

  }// cierre methods
}
</script>

<style>

</style>
