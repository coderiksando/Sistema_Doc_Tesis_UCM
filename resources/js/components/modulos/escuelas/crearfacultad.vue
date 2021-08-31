<template>
  <div>

    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">Crear Facultad</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div class="container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <a class="btn btn-info bnt-sm" href="javascript:history.go(-1)">
              <i class="fas fa-arrow-left"></i> Regresar
            </a>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Formulario de registro de facultades</h3>
                </div>
                    <div class="card-body">
                        <form role="form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Nombre</label>
                                        <div class="col-md-9">
                                            <input type="text" maxlength="40"  class="form-control" v-model="fillCrearFacultad.cNombre" @keyup.enter="setRegistrarEscuelas">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-4 offset-4">
                                <button class="btn btn-flat btn-info btnWidth" @click.prevent="setRegistrarFacultad" v-loading.fullscreen.lock="fullscreenLoading"
                            >{{globVar.btnSave}}</button>
                            <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriterios">{{globVar.btnClear}}</button>
                            </div>
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
            <h5 class="modal-title">Sistema de documentos UCM</h5>
            <button class="close" @click="abrirModal"></button>
          </div>
          <div class="modal-body">
            <div class="callout callout-danger" style="padding: 5px" v-for="(item, index) in mensajeError" :key="index" v-text="item"></div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="abrirModal">{{globVar.btnClose}}</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import globVar from '../../../services/globVar';
export default {
  data(){
    return{
      globVar: new globVar(),
      fillCrearFacultad:{
        cNombre: ''
      },
      listFacultades: [],
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
  mounted(){
    this.getListarFacultades();
  },
  methods:{
    limpiarCriterios(){
      this.fillCrearFacultad.cNombre = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },

    validarRegistrarFacultad(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillCrearFacultad.cNombre){
          this.mensajeError.push("El nombre es un campo obligatorio")
        }
        if (this.listFacultades.find(facultad => facultad.nombre == this.fillCrearFacultad.cNombre)) {
          this.mensajeError.push("La facultad que intenta crear ya existe.")
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    setRegistrarFacultad(){
      if(this.validarRegistrarFacultad()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/administracion/escuelas/setRegistrarFacultad'
      axios.post(url, {
        'cNombre'            : this.fillCrearFacultad.cNombre
      }).then(response => {
        this.fullscreenLoading = false;
        this.$router.push('/escuelas');
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
    getListarFacultades(){
      this.fullscreenLoading = true;
      var url = '/administracion/escuelas/getListarFacultades'
      axios.get(url, {
      }).then(response => {
          this.listFacultades = response.data;
          this.fullscreenLoading = false;
      })
    },

  }// cierre methods
}
</script>

<style>

</style>
