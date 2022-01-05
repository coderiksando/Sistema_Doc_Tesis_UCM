<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Formulario de registro de usuario</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-4 col-form-label">Nombres</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" v-model="fillCrearUsuarios.cNombre" @keyup.enter="setRegistrarUsuario">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-4 col-form-label">Apellidos</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" v-model="fillCrearUsuarios.cApellido" @keyup.enter="setRegistrarUsuario">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-4 col-form-label">Correo</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" v-model="fillCrearUsuarios.cCorreo" @keyup.enter="setRegistrarUsuario">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                      <label class="col-md-4 col-form-label">Asignar escuela</label>
                      <div class="col-md-8">
                          <el-select v-model="fillCrearUsuarios.cEscuela"
                            placeholder="Asignar Escuela"
                            filterable
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
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-4 col-form-label">Contraseña</label>
                    <div class="col-md-8">
                      <div class="input-group">
                        <input id="txtPassword" type="password" class="form-control" v-model="fillCrearUsuarios.cContrasena" @keyup.enter="setRegistrarUsuario" @change="checkPassword">
                        <div class="input-group-append">
                          <button id="show_password" class="btn btn-primary" type="button" @click.prevent="showPassword('txtPassword')"> <span id="txtPasswordIcon" class="fa fa-eye-slash icon txtPasswordIco"></span> </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-4 col-form-label">Repetir Contraseña</label>
                    <div class="col-md-8">
                      <div class="input-group">
                        <input id="txtConfPassword" type="password" class="form-control" :class="{ 'is-invalid' : passError}" v-model="fillCrearUsuarios.cConfContrasena" @keyup.enter="setRegistrarUsuario" @change="checkPassword">
                        <div class="input-group-append">
                          <button id="show_confpassword" class="btn btn-primary" type="button" @click.prevent="showPassword('txtConfPassword')"> <span id="txtConfPasswordIcon" class="fa fa-eye-slash icon txtConfPasswordIco"></span> </button>
                        </div>
                      </div>
                      <div class="custom-file invalid-feedback no-margin" v-show="passError">
                        Las constraseñas no coinciden.
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-4 col-form-label">Rol</label>
                    <div class="col-md-8">
                      <multiselect
                        v-model="listRoles.value"
                        mode="tags"
                        label='name'
                        track-by="id"
                        placeholder="Seleccionar roles"
                        :options="listRoles.options"
                        :multiple="true"
                        selectLabel="Seleccionar"
                        selectedLabel="Seleccionado"
                        deselectLabel="Presiona enter para remover">
                        <span slot="noResult">Rol no encontrado.</span>
                      </multiselect>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-4 col-form-label">Rut</label>
                    <div class="col-md-8">
                        <input type="text" placeholder="12345678-9" class="form-control" :class="{ 'is-invalid' : rutError}" v-model="fillCrearUsuarios.cRut" @keyup.enter="setRegistrarUsuario">
                        <div class="custom-file invalid-feedback no-margin" v-show="rutError">
                            Formato de RUT invalido.
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="setRegistrarUsuario" v-loading.fullscreen.lock="fullscreenLoading"
                  >{{globVar.btnSave}}</button>
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
      fillCrearUsuarios:{
        cNombre: '',
        cApellido: '',
        cCorreo: '',
        cContrasena: '',
        cConfContrasena: '',
        cEscuela: '',
        cRut: '',
        oFotografia: '',
      },
      listRoles: {
        value: [],
        options : []
      },
      listEscuelas:[],
      form : new FormData,
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
      passError: false,
      rutError: false
    }
  },
  computed: {

  },
  mounted(){
    EventBus.$emit('navegar', 'Crear usuario');
    this.getListarRoles();
    this.getListarEscuelas();
  },

  methods:{
    getListarEscuelas(){
      this.fullscreenLoading = true;
      var url = '/administracion/escuelas/getListarEscuelas'
      axios.get(url, {

      }).then(response => {
          this.listEscuelas = response.data;
          this.fullscreenLoading = false;
      })
    },
    getListarRoles(){
      this.fullscreenLoading = true;
      var url = '/administracion/roles/getListarRoles'
      axios.get(url, {

      }).then(response => {
          //this.inicializarPaginacion();
          this.listRoles.options = response.data;
          this.fullscreenLoading = false;
      })
    },
    getFile(e){
      this.fillCrearUsuarios.oFotografia = e.target.files[0];
    },
    limpiarCriterios(){
      this.fillCrearUsuarios.cNombre = '';
      this.fillCrearUsuarios.cApellido = '';
      this.fillCrearUsuarios.cCorreo = '';
      this.fillCrearUsuarios.cContrasena = '';
      this.fillCrearUsuarios.cConfContrasena = '';
      this.fillCrearUsuarios.cEscuela = '';
      this.fillCrearUsuarios.cRut = '';
      this.fillCrearUsuarios.oFotografia = '';
      this.listRoles.value = [];
      this.passError = false;
      this.rutError = false;
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    setRegistrarUsuario(){
      if (this.validarRegistrarUsuario()){
          this.modalShow = true;
          return;
      }
        this.fullscreenLoading = true;
        this.setGuardarUsuario();
    },
    setRegistrarArchivo(){
      this.form.append('file', this.fillCrearUsuarios.oFotografia)
      const config = { headers: {'Content-Type': 'multipart/form-data'}}
      var url = '/archivo/setRegistrarArchivo'
      axios.post(url, this.form, config).then(response =>{
        console.log(response)
        var nIdFile = response.data[0].nIdFile;
        this.setGuardarUsuario(nIdFile);
      })
    },
    validarRegistrarUsuario(){
      this.error = 0;
      this.mensajeError = [];

        if(!this.fillCrearUsuarios.cNombre){
          this.mensajeError.push("El nombre es un campo obligatorio")
        }
        if(!this.fillCrearUsuarios.cRut){
          this.mensajeError.push("El rut es un campo obligatorio")
        }
        if(this.rutError){
          this.mensajeError.push("El formato de RUT es invalido")
        }
        if(!this.fillCrearUsuarios.cApellido){
          this.mensajeError.push("El apellido es un campo obligatorio")
        }
        if(!this.fillCrearUsuarios.cCorreo){
          this.mensajeError.push("El correo es un campo obligatorio")
        }
        if(!this.fillCrearUsuarios.cContrasena){
          this.mensajeError.push("La contraseña es un campo obligatorio")
        }
        if(!this.fillCrearUsuarios.cEscuela){
          this.mensajeError.push("La escuela es un campo obligatorio")
        }
        if(this.listRoles.value.length == 0){
          this.mensajeError.push("El rol es un campo obligatorio")
        }
        if (this.fillCrearUsuarios.cContrasena != this.fillCrearUsuarios.cConfContrasena) {
          this.mensajeError.push("Las contraseñas no coinciden")
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    setGuardarUsuario(nIdFile){
      var url = '/administracion/usuario/setRegistrarUsuario'
      axios.post(url, {
        'cNombre'    : this.fillCrearUsuarios.cNombre,
        'cApellido'  : this.fillCrearUsuarios.cApellido,
        'cCorreo'    : this.fillCrearUsuarios.cCorreo,
        'cContrasena': this.fillCrearUsuarios.cContrasena,
        'cEscuela'   : this.fillCrearUsuarios.cEscuela,
        'cRut'       : this.fillCrearUsuarios.cRut,
        'oFotografia': nIdFile
      }).then(response => {
        if (response.data) {
          this.setEditarRolByUsuario(response.data);
        }else{
          this.mensajeError.push("El correo está en uso.")
          this.fullscreenLoading = false;
          this.modalShow = true;
        }
      })
    },
    setEditarRolByUsuario(nIdUsuario){
        this.fullscreenLoading = false;
        //this.$router.push('/usuarios');

        var url = '/administracion/usuario/setEditarRolByUsuario'
        axios.post(url, {
        'nIdUsuario'    : nIdUsuario,
        'nIdRol'  : this.listRoles.value,
      }).then(response => {
        //console.log("Registro Usuario exitosamente");
        this.fullscreenLoading = false;
        this.$router.push('/usuarios');
      })
    },
    showPassword(idInput){
      var cambio = document.getElementById(idInput);
      if(cambio.type == "password"){
        cambio.type = "text";
        $('.' + idInput + 'Ico').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
      }else{
        cambio.type = "password";
        $('.' + idInput + 'Ico').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
      }
    },
    checkPassword(){
      if (this.fillCrearUsuarios.cContrasena != this.fillCrearUsuarios.cConfContrasena){
        if (this.fillCrearUsuarios.cConfContrasena != '') {
          this.passError = true;
        }
      }else{
        this.passError = false;
      }
    },
    validarIngresoUsuarioRut(rut){
            if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test( rut )) {
                this.rutError = true;
            }
            var tmp 	= rut.split('-');
            var digv	= tmp[1];
            var rut 	= tmp[0];
            if ( digv == 'K' ) digv = 'k' ;
            if (this.digitoVerificador(rut) == digv) {
                this.rutError = false;
            } else {
                this.rutError = true;
            }
        },
        digitoVerificador(T){
            var M=0,S=1;
            for(;T;T=Math.floor(T/10))
                S=(S+T%10*(9-M++%6))%11;
            return S?S-1:'k';
        },

  }// cierre methods
}
</script>

<style>

</style>
