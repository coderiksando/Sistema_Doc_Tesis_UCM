<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
              <h3 class="card-title">Formulario de edición de area de documento</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-md-3 col-form-label">Nombre</label>
                      <div class="col-md-9">
                          <input type="text" class="form-control" v-model="fillEditarAreaTesis.cNombre" @keyup.enter="setEditarAreaTesis">
                      </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                  <label class="col-md-3 col-form-label">Escuela</label>
                    <div class="col-md-9">
                      <el-select v-model="fillEditarAreaTesis.nIdEscuela"
                      placeholder="Asignar Escuela"
                      clearable>
                        <el-option
                        v-for="item in listEscuelas"
                        :key="item.id"
                        :label="item.nombre"
                        :value="item.id">
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
                  <button class="btn btn-flat btn-info btnWidth" @click.prevent="setEditarAreaTesis" v-loading.fullscreen.lock="fullscreenLoading">{{globVar.btnSave}}</button>
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
            <h5 class="modal-title">Sistema de documento UCM</h5>
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
      fillEditarAreaTesis:{
        nIdAreaTesis: this.$attrs.id,
        cNombre: '',
        nIdEscuela: this.$attrs.id_escuela
      },
      listEscuelas:[],
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
      EventBus.$emit('navegar', 'Editar Areas de documento');
      this.getListarEscuelas();
      this.getListarAreaTesis();
  },
  methods:{
    limpiarCriterios(){
      this.fillEditarAreaTesis.cNombre = '';
      this.fillEditarAreaTesis.nIdEscuela = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    validarEditarAreaTesis(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillEditarAreaTesis.cNombre){
          this.mensajeError.push("El nombre es un campo obligatorio")
        }
        if(!this.fillEditarAreaTesis.nIdEscuela){
          this.mensajeError.push("La escuela es un campo obligatorio")
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    setEditarAreaTesis(){
      if(this.validarEditarAreaTesis()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/administracion/areatesis/setEditarAreaTesis'
      axios.post(url, {
        'id'      :this.fillEditarAreaTesis.nIdAreaTesis,
        'nombre'           :this.fillEditarAreaTesis.cNombre,
        'id_escuela'        :this.fillEditarAreaTesis.nIdEscuela
      }).then(response => {
        this.fullscreenLoading = false;
        this.$router.push('/areatesis');
        Swal.fire({
        icon: 'success',
        title: 'Area de documento editada correctamente',
        showConfirmButton: false,
        timer: 1500
      })
      }).catch(response=>{
        this.fullscreenLoading = false;
        this.$router.push('/areatesis');
        Swal.fire({
        icon: 'error',
        title: 'Area de documento ya existe',
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
    getListarAreaTesis(){
      this.fullscreenLoading = true;
      var url = '/administracion/areatesis/getListarAreaTesis'
      axios.get(url, {
        params: {
          'nIdAreaTesis' : this.fillEditarAreaTesis.nIdAreaTesis,
          'nIdEscuela' : this.fillEditarAreaTesis.nIdEscuela,
        }
      }).then(response => {
          //this.inicializarPaginacion();
          this.fillEditarAreaTesis.cNombre = response.data[0].nombre;
          this.fillEditarAreaTesis.nIdEscuela = response.data[0].id_escuela;
          this.fullscreenLoading = false;
      })
    },
    getListarEscuelas(){
      this.fullscreenLoading = true;
      var url = '/administracion/escuelas/getListarEscuelas'
      axios.get(url, {
      }).then(response => {
          this.listEscuelas = response.data;
          this.fullscreenLoading = false;
      })
    },


  }// cierre methods
}
</script>

<style>

</style>
