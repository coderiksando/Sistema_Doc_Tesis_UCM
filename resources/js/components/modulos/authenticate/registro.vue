<template>
<div class="login-page">
    <div style="max-height: 100%;" class="register-box">
        <div class="register-logo">
            <a href="/"><b>Sistema de seguimiento de documentos </b><p>Facultad de Ingenieria UCM</p></a>
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Registro de alumno</p>

                <form method="post">
                    <div>Nombres</div>
                    <div class="input-group mb-3">
                        <input type="text" maxlength="30" v-model="fillRegistrarAlumno.cNombre" @keyup.enter="setRegistrarAlumno" class="form-control" placeholder="Nombres" >
                        <div class="input-group-append">
                            <div class="input-group-text">
                            <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div>Apellidos</div>
                    <div class="input-group mb-3">
                        <input type="text" maxlength="30" v-model="fillRegistrarAlumno.cApellido" @keyup.enter="setRegistrarAlumno" class="form-control" placeholder="Apellidos" >
                        <div class="input-group-append">
                            <div class="input-group-text">
                            <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div>Rut</div>
                    <div class="input-group mb-3">
                        <input type="text" maxlength="30" v-model="fillRegistrarAlumno.cRut" @keyup.enter="setRegistrarAlumno" class="form-control" placeholder="Rut: 11111111-1" @blur="validarIngresoUsuarioRut($event.target.value)">
                        <div class="input-group-append">
                            <div class="input-group-text">
                            <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <p style="margin-top: 0rem; margin-bottom: 0rem;" class="invalid-feedback" v-show="addUserErrorMessage.rut === 1">Rut inválido</p>
                    </div>
                    <div>Correo</div>
                    <div class="input-group mb-3">
                        <input type="email" maxlength="50" v-model="fillRegistrarAlumno.email" @keyup.enter="setRegistrarAlumno" class="form-control" placeholder="Email" >
                        <div class="input-group-append">
                            <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div>Contraseña</div>
                    <div class="input-group mb-3">
                        <input type="password" v-model="fillRegistrarAlumno.cContrasena" @keyup.enter="setRegistrarAlumno" class="form-control" placeholder="Password" >
                        <div class="input-group-append">
                            <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                </form>
                   <div class="social-auth-links text-center mb-3">
                        <button class="btn btn-flat btn-block btn-danger" @click.prevent="setRegistrarAlumno" v-loading.fullscreen.lock="fullscreenLoading">
                        Registrarme
                        </button>
                    </div>
                <Router-link :to="{name:'login'}">
                    <b>Ya tengo una cuenta</b>
                </Router-link>
            </div>
        <!-- /.form-box -->
        </div><!-- /.card -->
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
                    <button class="btn btn-secondary" @click="abrirModal">Cerrar</button>
                </div>
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
            fillRegistrarAlumno:{
                cNombre: '',
                cApellido: '',
                cRut: '',
                email: '',
                cContrasena: '',
                nIdRol:  2
            },
            EmailError:[],
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
            addUserErrorMessage: {
                boton: true,
                nombre: 2,
                email: 2,
                rut: 2
            }
        }
    },
    mounted(){
        this.getParametros();
    },
    methods:{
        abrirModal(){
        this.modalShow = !this.modalShow;
        },
        setRegistrarAlumno(){
            if (this.validarRegistrarAlumno()){
                this.modalShow = true;
                return;
            }
            this.setGuardarAlumno();
        },
        validarRegistrarAlumno(){
            this.error = 0;
            this.mensajeError = [];
            if(!this.fillRegistrarAlumno.cNombre){
                this.mensajeError.push("El nombre es un campo obligatorio")
            }
            if(!this.fillRegistrarAlumno.cApellido){
                this.mensajeError.push("El apellido es un campo obligatorio")
            }
            if(!this.fillRegistrarAlumno.email){
                this.mensajeError.push("El correo es un campo obligatorio")
            }
            if(!this.fillRegistrarAlumno.cContrasena){
                this.mensajeError.push("La contraseña es un campo obligatorio")
            }
            if(!this.fillRegistrarAlumno.cRut){
                this.mensajeError.push("El RUT es un campo obligatorio")
            }
            if (this.addUserErrorMessage.rut === 1) {
                this.mensajeError.push("El RUT no es válido")
            }
            if(this.mensajeError.length){
                this.error = 1;
            }
            return this.error;
        },
        setGuardarAlumno(nIdFile){
            var url = 'authenticate/registro/setRegistrarAlumno'
            axios.post(url, {
                'cNombre'       : this.fillRegistrarAlumno.cNombre,
                'cApellido'     : this.fillRegistrarAlumno.cApellido,
                'cRut'          : this.fillRegistrarAlumno.cRut,
                'email'         : this.fillRegistrarAlumno.email,
                'cContrasena'   : this.fillRegistrarAlumno.cContrasena
            }).then(response => {
                // console.log(response.data);
                // this.setEditarRolAlumno(response.data);
                this.fullscreenLoading = false;
                this.$router.push('/');
                Swal.fire({
                    icon: 'success',
                    title: 'Usuario registrado con exito',
                    showConfirmButton: false,
                    timer: 2500
                })
            }).catch(error => {
                this.error = 0;
                this.mensajeError = [];
                if (error.response.status == 422){
                    this.mensajeError.push("Este correo ya esta registrado en nuestro sistema.")
                    this.error = 1;
                    this.modalShow = true;
                    return;
                }
                else{
                    this.mensajeError.push(error.response.data.error);
                    this.error = 1;
                    this.modalShow = true;
                    return;
                }
            })
        },
        setEditarRolAlumno(nIdUsuario){
            this.fullscreenLoading = false;
            //this.$router.push('/usuarios');

            var url = '/authenticate/registro/setEditarRolAlumno'
            axios.post(url, {
                'nIdUsuario'    : nIdUsuario,
                'nIdRol'  : this.fillRegistrarAlumno.nIdRol,
            }).then(response => {
                //console.log("Registro Usuario exitosamente");
                this.fullscreenLoading = false;
                this.$router.push('/');
            })
        },
        validarIngresoUsuarioRut(rut){
            if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test( rut )) {
                this.addUserErrorMessage.rut = true;
            }
            var tmp 	= rut.split('-');
            var digv	= tmp[1];
            var rut 	= tmp[0];
            if ( digv == 'K' ) digv = 'k' ;
            if (this.digitoVerificador(rut) == digv) {
                this.addUserErrorMessage.rut = 0;
            } else {
                this.addUserErrorMessage.rut = 1;
            }
        },
        digitoVerificador(T){
            var M=0,S=1;
            for(;T;T=Math.floor(T/10))
                S=(S+T%10*(9-M++%6))%11;
            return S?S-1:'k';
        },
        getParametros(){
            this.fullscreenLoading = true;
            var url = '/admin/parametros';
            axios.post(url,{'params': ['HabilitarRegistro']}).then(response => {
                let enableReg = parseInt(response.data[0][0]);
                if (!enableReg) {
                    this.$router.push('/');
                }
                this.fullscreenLoading = false;
            })
        }
    },
}
</script>

<style>

</style>
