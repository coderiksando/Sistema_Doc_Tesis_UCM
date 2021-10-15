<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Acta de reunión</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-12">
                    <label>Seleccionar alumno</label>
                    <div class="col-md-12">
                      <Multiselect
                        v-model="selectedFit"
                        placeholder="Seleccionar estudiante"
                        :options="listFits"
                        label = "nombres"
                        selectLabel="Seleccionar"
                        selectedLabel="Seleccionado"
                        deselectLabel="Remover"
                        >
                      <template slot="noResult">No hay resultados</template>
                      <template slot="noOptions">Lista vacía</template>
                      </Multiselect>
                    </div>
                    <label class="mt-2">Acuerdo</label>
                    <div class="col-md-12">
                      <div id="app">
                        <ckeditor v-model="fillCrearBitacora.Acuerdo"></ckeditor>
                      </div>
                    </div>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="setRegistrarBitacora" v-loading.fullscreen.lock="fullscreenLoading">{{globVar.btnSave}}</button>
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
import Multiselect from 'vue-multiselect';
import globVar from '../../../services/globVar';
export default {
  components: { Multiselect },
  data(){
    return{
      globVar: new globVar(),
      fillCrearBitacora:{
        id_user: '',
        Acuerdo: ''
      },
      listFits:[],
      selectedFit: [],
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
      mensajeError:[],
    }
  },
  computed: {
  },
  mounted(){
    EventBus.$emit('navegar', 'Crear Acta de reunión');  
    this.getListarFitsByprofesor();
  },
  methods:{
    getListarFitsByprofesor(){
      this.fullscreenLoading = true;
      this.selectedFit = [];
      var url = '/avances/getListarFitsByprofesor';
      axios.get(url, {
        params: {
          'estado'    : 'D'
        }
        }).then(response => {
          this.listFits = response.data;
          this.fullscreenLoading = false;
      })
    },
    limpiarCriterios(){
      this.selectedFit = [];
      this.fillCrearBitacora.Acuerdo = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    validarRegistrarBitacora(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.selectedFit.id){
          this.mensajeError.push("El alumno es un campo obligatorio")
        }
        if(!this.fillCrearBitacora.Acuerdo){
          this.mensajeError.push("El acuerdo es un campo obligatorio")
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    setRegistrarBitacora(){
      if(this.validarRegistrarBitacora()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/bitacoras/setRegistrarBitacora'
      axios.post(url, {
        'fit'           : this.selectedFit.id,
        'Acuerdo'       : this.fillCrearBitacora.Acuerdo
      }).then(response => {
        this.fullscreenLoading = false;
        this.$router.push('/bitacoras');
        Swal.fire({
        icon: 'success',
        title: 'Acta de reunión registrada correctamente',
        showConfirmButton: false,
        timer: 1500
      })
      }).catch(response=>{
        this.fullscreenLoading = false;
        Swal.fire({
        icon: 'error',
        title: 'Error al crear Acta de reunión',
        showConfirmButton: false,
        timer: 1500
      })
      })
    }



  }// cierre methods
}
</script>

<style>

</style>
