<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
              <h3 class="card-title">Formulario registrar vinculación</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Nombre</label>
                    <div class="col-md-9">
                        <input type="text" maxlength="50" class="form-control" v-model="fillCrearVinculacion.cNombre" @keyup.enter="setRegistrarVinculacion">
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Tipos</label>
                    <div class="col-md-9">
                      <el-select v-model="fillCrearVinculacion.cTipo"
                      placeholder="Seleccione un Tipo"
                      clearable>
                      <el-option
                          v-for="item in listTipo"
                          :key="item.value"
                          :label="item.label"
                          :value="item.value">
                      </el-option>
                      </el-select>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Descripción</label>
                    <div class="col-md-9">
                        <input type="text" maxlength="70" class="form-control" v-model="fillCrearVinculacion.cDescripcion" @keyup.enter="setRegistrarVinculacion">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                  <button class="btn btn-flat btn-info btnWidth" @click.prevent="setRegistrarVinculacion" v-loading.fullscreen.lock="fullscreenLoading">{{globVar.btnSave}}</button>
              <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriterios">{{globVar.btnClear}}</button>
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
      fillCrearVinculacion:{
        cNombre: '',
        cTipo: '',
        cDescripcion:''
      },
      listTipo: [
        {value: 'Fondo Concursable', label: 'Fondo Concursable'},
        {value: 'Empresa', label: 'Empresa'},
        {value: 'Comunidad', label: 'Comunidad'}
      ],
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
    EventBus.$emit('navegar', 'Crear vinculación');
  },
  methods:{
    limpiarCriterios(){
      this.fillCrearVinculacion.cNombre = '';
      this.fillCrearVinculacion.cTipo = '';
      this.fillCrearVinculacion.cDescripcion = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },

    validarRegistrarVinculacion(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillCrearVinculacion.cNombre){
          this.mensajeError.push("El nombre es un campo obligatorio")
        }
        if(!this.fillCrearVinculacion.cTipo){
          this.mensajeError.push("El Tipo es un campo obligatorio")
        }
        if(!this.fillCrearVinculacion.cDescripcion){
          this.mensajeError.push("la Descripcion es un campo obligatorio")
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    setRegistrarVinculacion(){
      if(this.validarRegistrarVinculacion()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/administracion/vinculacion/setRegistrarVinculacion'
      axios.post(url, {
        'cNombre'            : this.fillCrearVinculacion.cNombre,
        'cTipo'              : this.fillCrearVinculacion.cTipo,
        'cDescripcion'       : this.fillCrearVinculacion.cDescripcion,
      }).then(response => {
        this.fullscreenLoading = false;
        this.$router.push('/vinculacion');
        Swal.fire({
        icon: 'success',
        title: 'Vinculación creada correctamente',
        showConfirmButton: false,
        timer: 1500
      })
      }).catch(response=>{
        this.fullscreenLoading = false;
        Swal.fire({
        icon: 'error',
        title: 'Vinculación ya existe',
        showConfirmButton: false,
        timer: 1500
      })
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


  }// cierre methods
}
</script>

<style>

</style>
