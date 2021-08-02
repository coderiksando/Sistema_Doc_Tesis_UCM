<template>
<div class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <router-link :to="{name:'login'}">
                <b>Recuperar contraseña</b>
            </router-link>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg"></p>

                <div class="alert alert-primary">
                    <form v-on:submit.prevent="sendToken">
                        <div class="form-group">
                            <label>1. Ingresa tu Correo</label>
                            <input type="text" class="form-control" v-bind="{'is-invalid': errorEmail, 'is-valid': infoEmail}" placeholder="Correo" v-model="email">
                            <div v-if="i==true" class="margenes" ><p class="invalid-feedback">{{errorEmail}}</p></div>
                            <div v-if="k==true" class="margenes"><p class="valid-feedback">{{infoEmail}}</p></div>
                            <button class="btn btn-primary">Enviar codigo a mi correo</button>
                        </div>
                    </form>
                </div>

                <div class="alert alert-secondary">
                    <form v-on:submit.prevent="validateToken">
                        <div class="form-group">
                            <label>2. Ingresa el codigo recibido</label>
                            <input type="text" class="form-control" v-bind:class="{ 'is-invalid:': errorToken, 'is-valid': infoToken}" placeholder="codigo" v-model="token">
                            <div class="invalid-feedback">
                                {{errorToken}}
                            </div>
                            <div class="invalid-feedback">
                                {{infoToken}}
                            </div>
                            <button type="submit" class="btn btn-secondary">Validar código</button>
                        </div>
                    </form>
                </div>

                <div class="alert alert-success" v-if="tokenValid">
                    <form v-on:submit.prevent="changePassword">
                        <div class="form-group">
                            <label>3. Ingresa tu nueva contraseña</label>
                            <input
                            type="password"
                            class="form-control"
                            v-model="newPassword"
                            v-bind:class="{ 'is-invalid': errorNewPassword}"
                            placeholder="nueva contraseña">
                            <div class="invalid-feedback">
                                {{errorNewPassword}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Repetir Contraseña</label>
                            <input
                            type="text"
                            class="form-control"
                            v-model="passwordAgain"
                            v-bind:class="{ 'is-invalid': errorPasswordAgain}"
                            placeholder="repetir contraseña">
                            <div class="invalid-feedback">
                                {{errorPasswordAgain}}
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Cambiar contraseña</button>
                    </form>
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
            k:false,
            i:false,
            email: '',
            errorEmail: null,
            infoEmail: null,
            token: '',
            errorToken: null,
            infoToken: null,
            newPassword: '',
            errorNewPassword: null,
            passwordAgain: '',
            errorPasswordAgain: null,
            tokenValid: false,
            id_user: null,

        }
    },
    methods: {
        sendToken(){
            this.errorEmail = null;
            this.i=false;
            this.k=false;
            if(!this.email){
                this.errorEmail = 'Ingrese un correo';
                this.i=true;
            }
            if(!this.errorEmail){
                const data = {
                    email: this.email
                }
                /*this.req.post('sendToken', data).then(() => {
                    this.infoEmail = 'Correo enviado';
                })*/
                var url = '/passrecovery/sendToken'
                axios.post(url, {
                    'email' : this.email,
                }).then(() => {
                    this.infoEmail = 'Correo enviado';
                    this.k=true;
                    this.i=false;
                }).catch(error => {
                    this.errorEmail = error.response.data.error;
                    this.i=true;
                    this.k=false;
                });
            }
        },
        validateToken(){
            this.errorToken = null;

            if(!this.token){
                this.errorToken = 'El codigo es requerido.';
            }
            if(!this.errorToken){
                const data = {
                    token: this.token
                }
                /*this.app.req.post('validateToken', data)*/
                var url = '/passrecovery/validateToken'
                axios.post(url, {
                    'token' : this.token,
                }).then(response => {
                    if(response.data.id_user){
                        this.tokenValid = true;
                        this.infoToken = "El codigo es valido"
                        this.id_user = response.data.id_user;
                    }
                }).catch(error => {
                    thi.errorToken = error.response.data.error;
                })
            }
        },
        changePassword(){
            this.errorNewPassword = null;
            this.errorPasswordAgain = null;

            if(!this.newPassword){
                this.errorNewPassword = "La contraseña es requerida.";
            }
            if(!this.passwordAgain){
                this.errorPasswordAgain = "Falta confirmar la contraseña";
            }
            if(this.newPassword !== this.passwordAgain){
                this.errorPasswordAgain = "Las contraseñas no coinciden";
            }
            if(!this.errorNewPassword && !this.errorPasswordAgain){
                var url = '/passrecovery/resetPassword'
                axios.post(url, {
                    'password': this.newPassword,
                    'id_user': this.id_user
                }).then(() => {
                    this.$router.push('/login');
                })
            }
        },

    },

}
</script>

<style>
.margenes{
    margin-bottom: 5px;
    margin-top: 5px;
}

p.invalid-feedback{
    display: inline;
    color: #525252;
    background: #ff5f5f;
    padding: 5px;
    border-radius: 10px;
    font-weight: 600;
}
p.valid-feedback{
    display: inline;
    color: #525252;
    background: #8df961;
    padding: 5px;
    border-radius: 10px;
    font-weight: 600;
}
</style>
