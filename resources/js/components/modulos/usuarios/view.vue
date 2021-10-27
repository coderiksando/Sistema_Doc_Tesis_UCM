<template>
  <div v-loading.fullscreen.lock="fullscreenLoading">
      <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold"></h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
        </div>

        <div class="content container-fluid">
            <div class="row">
                <div class="col-md-4">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">

                  <template v-if="!fillVerUsuarios.cRutaArchivo">
                        <img class="profile-user-img img-fluid img-circle img-max-heigth" src="/img/user.jpg" alt="User profile picture">
                  </template>
                  <template v-else>
                              <img :src="fillVerUsuarios.cRutaArchivo" :alt="fillVerUsuarios.cNombre" class="img-circle elevation-2" style="height:125px; width:125px !important;">
                  </template>
                </div>

                <h3 class="profile-username text-center">{{fillVerUsuarios.cNombre + ' ' + fillVerUsuarios.cApellido}}</h3>

                <!-- <p class="text-muted text-center">{{listRoles.value}}</p> -->


              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Acerca de mí</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-envelope-open-text"></i>Correo</strong>

                <p class="text-muted" v-text="fillVerUsuarios.cCorreo">
                </p>



              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

              <div class="col-md-8">
                <div class="card">
                  <div class="card-header p-2">
                    <ul class="nav nav-pills position-reverse">
                      <a class="btn btn-info bnt-sm" href="javascript:history.go(-1)">
                        <i class="fas fa-arrow-left"></i> {{globVar.btnBack}}
                      </a>
                    </ul>
                  </div><!-- /.card-header -->
                  <div class="card-body">
                    <div class="tab-content">

                      <div class="tab-pane active" id="settings">
                        <form class="form-horizontal">
                            <template v-if="listRolPermisosByUsuario.includes('EsAlumno')">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Escuela</label>
                                    <div class="col-md-9">
                                        <el-select v-model="fillEditarUsuarios.cEscuela"
                                        filterable
                                        placeholder="Seleccione escuela"
                                        clearable>
                                        <el-option
                                            v-for="item in listEscuela"
                                            :key="item.id"
                                            :label="item.nombre"
                                            :value="item.id">
                                        </el-option>
                                        </el-select>
                                    </div>
                                </div>
                            </template>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Rut</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" v-model="fillEditarUsuarios.cRut" @blur="verificacionRut">
                                    <div v-show="rutError"><p style="color: red; margin-bottom: 0px;">Rut ingresado no válido</p></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Nombres</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" v-model="fillEditarUsuarios.cNombre" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Apellidos</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" v-model="fillEditarUsuarios.cApellido" >
                                </div>
                            </div>
                            
                            <template v-if="listRolPermisosByUsuario.includes('EsAlumno')">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Correo</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" v-model="fillEditarUsuarios.cCorreo" >
                                </div>
                            </div>
                            </template>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Nueva contraseña</label>
                                <div class="col-md-9">
                                    <div v-if="addPassword" class="input-group">
                                        <input id="pass1" type="password" class="form-control" v-model="fillEditarUsuarios.cContrasena" @blur="passwordVerification">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" @click.prevent="showPassword('pass1')"><span id="pass1" class="fa fa-eye-slash icon txtPasswordIco"></span></button>
                                        </div>
                                    </div>
                                    <div v-if="!addPassword" class="noPadNoMar col-md-2">
                                        <button class="btn btn-info btnFull" @click.prevent="buttonAddPassword">
                                            <i class="fas fa-plus-square"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="addPassword" class="form-group row">
                                <label class="col-md-3 col-form-label">Repetir nueva contraseña</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input id="pass2" type="password" class="form-control" v-model="repeatedPassword" @blur="passwordVerification" autocomplete="off">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" @click.prevent="showPassword('pass2')"><span id="pass2" class="fa fa-eye-slash icon txtPasswordIco"></span></button>
                                        </div>
                                    </div>
                                    <div v-show="passwordError===1"><p style="color: red; margin-bottom: 0px;">Las contraseñas no coinciden</p></div>
                                    <div v-if="addPassword" class="noPadNoMar col-md-2">
                                        <button class="btn btn-info btnFull" @click.prevent="buttonAddPassword">
                                            <i class="fas fa-minus-square"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <template v-if="listRoles.value.find(esAlumno)">
                                <div class="form-group row">
                                <label class="col-md-3 col-form-label">Fecha de ingreso</label>
                                <div class="col-md-9">
                                    <el-date-picker
                                    style="width: 100%;"
                                    v-model="fillEditarUsuarios.f_entrada"
                                    type="month"
                                    placeholder="Fecha de inicio"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="pickerOptions"
                                    @change="selectStart">
                                    </el-date-picker>
                                </div>
                                </div>

                                <div class="form-group row">
                                <label class="col-md-3 col-form-label">Fecha de término</label>
                                <div class="col-md-9">
                                    <el-date-picker
                                    style="width: 100%;"
                                    v-model="fillEditarUsuarios.f_salida"
                                    type="month"
                                    placeholder="Fecha de término"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="endOption">
                                    </el-date-picker>
                                </div>
                                </div>
                            </template>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Dirección</label>
                                <div class="col-md-9">
                                    <input
                                    type="text"
                                    class="form-control"
                                    v-model="fillEditarUsuarios.direccion"
                                    />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Teléfono</label>
                                <div class="col-md-9">
                                    <input
                                    type="text"
                                    class="form-control"
                                    v-model="fillEditarUsuarios.telefono"
                                    />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Fotografía (opcional)</label>
                                <div class="col-md-9">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" :class="{ 'is-invalid' : formatError , 'is-valid' : hover}" @change="getFile" @mouseover="hover = true" @mouseleave="hover = false">
                                        <label class="custom-file-label" for="input1">{{fillEditarUsuarios.oFotografia ? fillEditarUsuarios.oFotografia.name : 'Seleccionar archivo'}}</label>
                                    </div>
                                    <div v-show="!formatError && !fillEditarUsuarios.oFotografia" class="custom-file">
                                        Los formatos de archivo soportados son:
                                        <span v-for="item in fileTypes" :key="item" v-text="item +' '"></span>
                                    </div>
                                    <div class="custom-file invalid-feedback" v-show="formatError">
                                        El formato del archivo no es soportado.
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Fecha de nacimiento (opcional)</label>
                                <div class="col-md-9">
                                    <el-date-picker
                                    style="width: 100%;"
                                    v-model="fillEditarUsuarios.f_nacimiento"
                                    placeholder="Fecha de nacimiento"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="pickerOptions">
                                    </el-date-picker>
                                </div>
                            </div>
                            <template>
                            <div class="form-group row" v-if="rolActivo == 'Administrador'">
                                <label class="col-md-3 col-form-label">Roles</label>
                                <div class="col-md-9">
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
                            </template>
                          <div class="form-group row">
                            <div class=" col-sm-6">
                              <button style="margin: 2px;" :disabled="formatError || rutError || passwordError===1" class="btn btn-info btnFull" @click.prevent="setEditarUsuario">{{globVar.btnSave}}</button>
                            </div>
                            <div class=" col-sm-6">
                              <button style="margin: 2px;" class="btn btn-primary btnFull" @click.prevent="reestablecerDatos">{{globVar.btnClear}}</button>
                            </div>
                          </div>
                        </form>
                      </div>
                      <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                  </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
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
            fillEditarUsuarios:{
                nIdUsuario: this.$attrs.id_user,
                cRut: '',
                cNombre: '',
                cApellido: '',
                cCorreo: '',
                cContrasena: '',
                cEscuela: '',
                oFotografia: '',
                cRutaArchivo: '',
                direccion: '',
                telefono: '',
                f_entrada: '',
                f_salida: '',
                f_nacimiento: '',
                idFotografia: ''
            },
            fillVerUsuarios:{
                nIdUsuario: this.$attrs.id_user,
                cNombre: '',
                cApellido: '',
                cCorreo: '',
                cContrasena: '',
                cEscuela: '',
                oFotografia: '',
                cRutaArchivo: '',
                cNombreRol: ''
            },
            form: new FormData,
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
            formatError : false,
            fileTypes: ['.jpg','.png',],
            dataUser: {},
            listEscuela: [],
            pickerOptions: {
                disabledDate(time) {
                    return time.getTime() > Date.now();
                }
            },
            endOption: {
            },
            rutError: false,
            fullscreenLoading:false,
            repeatedPassword: '',
            passwordError: 2,
            listRoles: {
                value: [],
                options: ['Admin', 'otro']
            },
            addPassword: false,
            rolActivo: JSON.parse(localStorage.getItem('rolActivo')),
            authUser: JSON.parse(localStorage.getItem('authUser')),
            listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
            hover: false
        }
    },
    mounted(){
        EventBus.$emit('navegar', 'Perfil del usuario');
        this.revisionDeSeguridad();
    },
    methods:{
        inicializacion () {
            this.fullscreenLoading = true;
            const yearCalculated = 5*365*24*60*60*1000;
            const month = (new Date().getMonth()) * 30*24*60*60*1000;
            this.fillEditarUsuarios.f_entrada = new Date(Date.now() - (yearCalculated + month));
            this.fillEditarUsuarios.f_salida = new Date(Date.now() - (month));
            this.selectStart();
        },
        revisionDeSeguridad() {
            if (this.rolActivo == 'Administrador' || this.authUser.id_user == this.fillEditarUsuarios.nIdUsuario) {
                this.inicializacion();
                this.getUsuarioById();
                this.getRolByUsuario();
                this.getListarEscuela();
                this.getListarRoles();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Estás editando un perfil que no te pertenece.',
                    showConfirmButton: false,
                    timer: 2500
                });
                this.$router.push('/dashboard');
            }
        },
        getFile(e){
            this.formatError = false;
            this.fillEditarUsuarios.oFotografia = e.target.files[0];
            if (!this.fillEditarUsuarios.oFotografia) return;
            const fileName = this.fillEditarUsuarios.oFotografia.name;
            var dots = fileName.split(".")
            var fileType = "." + dots[dots.length-1];
            if (this.fileTypes.join(".").indexOf(fileType) == -1){
                this.formatError = true;
            }
        },
        getRolByUsuario(){
            var url = '/administracion/usuario/getRolByUsuario'
            axios.get(url, {
                params:{
                'nIdUsuario' : this.fillVerUsuarios.nIdUsuario
                }
            }).then(response => {
                this.listRoles.value = response.data;
            })
        },
        getListarRoles(){
            var url = '/administracion/roles/getListarRoles'
            axios.get(url, {
            }).then(response => {
                //this.inicializarPaginacion();
                this.listRoles.options = response.data;
                this.fullscreenLoading = false;
            })
        },
        getUsuarioById(){
            var url = '/administracion/usuario/getListarUserById'
            // console.log("nIdUsuario", this.fillEditarUsuarios.nIdUsuario)
            axios.get(url, {
                params: {
                'nIdUsuario' : this.fillEditarUsuarios.nIdUsuario
                }
            }).then(response => {
                this.dataUser = response.data;
                // console.log(this.dataUser);
                this.getUsuario(response.data);
            })
        },
        getUsuario(data){
            this.fillVerUsuarios.cRut           = this.fillEditarUsuarios.cRut          = data.rut;
            this.fillVerUsuarios.cNombre        = this.fillEditarUsuarios.cNombre       = data.nombres;
            this.fillVerUsuarios.cApellido      = this.fillEditarUsuarios.cApellido     = data.apellidos;
            this.fillVerUsuarios.cCorreo        = this.fillEditarUsuarios.cCorreo       = data.email;
            this.fillVerUsuarios.cEscuela       = this.fillEditarUsuarios.cEscuela      = data.id_escuela;
            if (data.file) {
                this.fillVerUsuarios.cRutaArchivo   = this.fillVerUsuarios.cRutaArchivo     = data.file.path;
            }
            this.fillVerUsuarios.direccion      = this.fillEditarUsuarios.direccion     = data.direccion;
            this.fillVerUsuarios.telefono       = this.fillEditarUsuarios.telefono      = data.telefono;
            this.fillVerUsuarios.f_nacimiento   = this.fillEditarUsuarios.f_nacimiento  = data.birthday;
            this.fillVerUsuarios.f_entrada      = this.fillEditarUsuarios.f_entrada     = data.f_ingreso;
            this.fillVerUsuarios.f_salida       = this.fillEditarUsuarios.f_salida      = data.f_salida;
        },
        abrirModal(){
            this.modalShow = !this.modalShow;
        },
        setEditarUsuario(){
            if (this.validarRegistrarUsuario()){
                this.modalShow = true;
                return;
            }
            this.fullscreenLoading = true;
            if(!this.fillEditarUsuarios.oFotografia || this.fillEditarUsuarios.oFotografia == undefined){
                this.setGuardarUsuario(0);
            } else {
                this.setRegistrarArchivo();
            }
        },
        setRegistrarArchivo(){
            this.form.append('file', this.fillEditarUsuarios.oFotografia)
            const config = { headers: {'Content-Type': 'multipart/form-data'}}
            var url = '/archivo/setRegistrarArchivo'
            axios.post(url, this.form, config).then(response =>{
                // console.log(response)
                var nIdFile = response.data.id;
                this.setGuardarUsuario(nIdFile);
            })
        },
        setGuardarUsuario(nIdFile){
            this.fillEditarUsuarios.idFotografia = nIdFile;
            var url = '/administracion/usuario/setEditarUsuarioView'
            axios.post(url, this.fillEditarUsuarios).then(response => {
                // console.log(response);
                this.getRefrescarUsuarioAutenticado();
                this.getUsuarioById();
                this.setEditarRolByUsuario(this.fillEditarUsuarios.nIdUsuario);
            }).catch(error=>{
                this.mensajeError.push(error.response.data.error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error al ingresar datos',
                    showConfirmButton: false,
                    timer: 1500
                })
                this.fullscreenLoading = false;
                if (this.mensajeError.length > 0) {
                    this.modalShow = true;
                }
            })
        },
        setEditarRolByUsuario(nIdUsuario){
            var url = '/administracion/usuario/setEditarRolByUsuario'
            axios.post(url, {
            'nIdUsuario'    : nIdUsuario,
            'nIdRol'  : this.listRoles.value,
            }).then(response => {
                this.fullscreenLoading = false;
            })
        },
        getRefrescarUsuarioAutenticado(){
            var url = '/authenticate/getRefrescarUsuarioAutenticado'
            axios.get(url).then(response => {
                // console.log(response.data);
                EventBus.$emit('verifyAuthenticatedUser', response.data);
                this.fullscreenLoading = false;
                this.getUsuarioById();
                Swal.fire({
                    icon: 'success',
                    title: 'Se actualizo correctamente',
                    showConfirmButton: false,
                    timer: 1500
                })
                this.$router.push('/usuarios');
            })
        },
        validarRegistrarUsuario(){
            this.error = 0;
            this.mensajeError = [];
            if(!this.fillEditarUsuarios.cNombre){
                this.mensajeError.push("El nombre es un campo obligatorio")
            }
            if(!this.fillEditarUsuarios.cApellido){
                this.mensajeError.push("El apellido es un campo obligatorio")
            }
            if(!this.fillEditarUsuarios.cCorreo){
                this.mensajeError.push("El correo es un campo obligatorio")
            }
            if(!this.fillEditarUsuarios.cEscuela){
                this.mensajeError.push("La carrera es un campo obligatorio")
            }
            if(!this.fillEditarUsuarios.direccion){
                this.mensajeError.push("La dirección es un campo obligatorio")
            }
            if(!this.fillEditarUsuarios.telefono){
                this.mensajeError.push("El teléfono es un campo obligatorio")
            }
            if(!this.fillEditarUsuarios.f_entrada && this.listRoles.value.find(this.esAlumno)){
                this.mensajeError.push("La fecha de ingreso a la carrera es un campo obligatorio")
            }
            if(!this.fillEditarUsuarios.f_salida && this.listRoles.value.find(this.esAlumno)){
                this.mensajeError.push("La fecha de término de asignaturas es un campo obligatorio")
            }
            if(this.listRoles.value.length == 0){
                this.mensajeError.push("Los usuarios deben poseer al menos 1 rol")
            }
            if(this.mensajeError.length){
                this.error = 1;
            }
            return this.error;
        },
        reestablecerDatos() {
            this.fillEditarUsuarios.cRut            = this.fillVerUsuarios.cRut;
            this.fillEditarUsuarios.cNombre         = this.fillVerUsuarios.cNombre;
            this.fillEditarUsuarios.cApellido       = this.fillVerUsuarios.cApellido;
            this.fillEditarUsuarios.cCorreo         = this.fillVerUsuarios.cCorreo;
            this.fillEditarUsuarios.cEscuela        = this.fillVerUsuarios.cEscuela;
            this.fillEditarUsuarios.oFotografia     = '';
            this.fillEditarUsuarios.cContrasena     = '';
            this.repeatedPassword                   = '';
            this.fillEditarUsuarios.direccion       = this.fillVerUsuarios.direccion;
            this.fillEditarUsuarios.telefono        = this.fillVerUsuarios.telefono;
            this.fillEditarUsuarios.f_nacimiento    = this.fillVerUsuarios.f_nacimiento;
            this.fillEditarUsuarios.f_entrada       = this.fillVerUsuarios.f_entrada;
            this.fillEditarUsuarios.f_salida        = this.fillVerUsuarios.f_salida;
            this.rutError = false;
            this.formatError = false;
        },
        getListarEscuela() {
            this.fullscreenLoading = true;
            const url = '/administracion/escuelas/getListarEscuelas';
            axios.get(url, {
            }).then(response => {
                this.listEscuela = response.data;
                this.fullscreenLoading = false;
            })
        },
        selectStart() {
            this.endOption = {
                disabledDate: (time) => {
                    return time.getTime() < Date.parse(this.fillEditarUsuarios.f_entrada) || time.getTime() > Date.now();
                }
            };
        },
        verificacionRut () {
            if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test( rut )) {
                this.rutError = true;
            }
            var tmp 	= this.fillEditarUsuarios.cRut.split('-');
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
        passwordVerification () {
            this.passwordError = 0;
            this.passwordVerification;
            if (this.repeatedPassword !== this.fillEditarUsuarios.cContrasena) {
                this.passwordError = 1;
            }
        },
        showPassword (idPass) {
            const cambio = document.getElementById(idPass);
            if (cambio.type == 'password') {
                cambio.type = 'text';
                $('.' + idPass + 'Ico').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
            } else {
                cambio.type = 'password';
                $('.' + idPass + 'Ico').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
            }
        },
        esAlumno(rol){
            return rol.name == 'Alumno';
        },
        buttonAddPassword () {
            if (this.addPassword) {
                this.fillEditarUsuarios.cContrasena = null;
                this.repeatedPassword = null;
                this.passwordError = 0;
            }
            this.addPassword = !this.addPassword;
        }
    }
}
</script>

<style>
  .position-reverse{
    flex-direction: row-reverse !important;
  }
  .img-mac-height{
    max-height: 100px !important;
  }
</style>
