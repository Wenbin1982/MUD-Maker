<template>
        <transition name="slide-fade" mode="out-in">
        <div class="auth_main">

            <aside>
                <div id="carousel" class="carousel slide carousel-fade " data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carousel" v-for="(item, index) in slideText"
                            :class="{ 'active': index === 0 }" :data-slide-to="index"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item" v-for="(item, index) in slideText"
                             :class="{ 'active': index === 0 }">
                            <h1> {{item.title}}</h1>
                            <p>{{item.text}}</p>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="main_content" v-if="showAuth">
                <section class="section_auth section__new load_content" :class="[ load ? 'load_show' : '' ]">
                    <component
                            v-for="component in componentNames"
                            v-bind:is="component"
                            v-bind:key="component"
                    >
                    </component>
                </section>
            </div>
            <div class="modal fade" id="authModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>User settings:</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>{{modalText}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="resetPassword" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Password update:</h4>
                        </div>
                        <div class="modal-body">
                            <transition name="slide-fade" mode="out-in">
                                <div class="row" v-if="!ressetPassText">
                                    <div class="col-md-12">
                                        <label>Password:</label>
                                        <input type="password"
                                               name="update_password"
                                               v-model="changePassword.password"
                                               class="form-control"
                                               v-validate="{ required: true, min: 6 }"
                                               :class="{'input': true, 'is-danger': errors.has('update_password') }">

                                        <div v-show="errors.has('password')" class="help is-danger">
                                            {{ errors.first('update_password') }}
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Confirm the password:</label>
                                        <input type="password"
                                               name="update_confirmation_password"
                                               data-vv-as="password"
                                               v-model="changePassword.confirm_password"
                                               class="form-control"
                                               v-validate="{ confirmed: 'update_password', required: true, min: 6 }"
                                               :class="{'input': true, 'is-danger': errors.has('update_confirmation_password') }">

                                        <div v-show="errors.has('update_confirmation_password')" class="help is-danger">
                                            {{ errors.first('update_confirmation_password') }}
                                        </div>
                                    </div>
                                </div>
                                <h3 v-if="ressetPassText">{{ressetPassText}}</h3>
                            </transition>

                        </div>

                        <div class="modal-footer">
                            <button type="button" v-if="!ressetPassText" @click="saveSettingsPassword" class="btn btn-primary remove_btn">
                                Save settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import singIn from './SingIn.vue';
    import singUp from './SingUp.vue';
    import singUpSuccess from './SingUpSuccess.vue';
    import forgotPass from './ForgotPass.vue';
    import singInWaitActivation from './SingInWaitActivation.vue';
    import forgotPassSuccess from './ForgotPassSuccess.vue';
    import {mapGetters, mapActions} from 'vuex';

    export default {
        data() {
            return {
                showAuth: true,
                ressetPassText: null,
                changePassword: {
                    password: null,
                    confirm_password: null
                },
                componentNames: ['singIn', 'singUp', 'forgotPass', 'forgotPassSuccess', 'singUpSuccess', 'singInWaitActivation'],
                load: false,
                slideText: [
                    {
                        title: 'MUD File Maker',
                        text: 'This page will help you create a Manufacturer Usage Description (MUD) file for your web site.'
                    },
                    {
                        title: 'MUD File Maker',
                        text: 'This page will help you create a Manufacturer Usage Description (MUD) file for your web site.'
                    },
                    {
                        title: 'MUD File Maker',
                        text: 'This page will help you create a Manufacturer Usage Description (MUD) file for your web site.'
                    }
                ]
            }
        },
        components: {
            singIn, singUp, forgotPass, forgotPassSuccess, singUpSuccess, singInWaitActivation
        },
        computed: {
            modalText() {
                return this.$store.state.modalText
            },
        },
        mounted() {
            this.$nextTick(() => {
                if (this.$route.params.key) {
                    $('#resetPassword').modal('show');
                    this.showAuth = !this.showAuth;
                }
            });
            setTimeout(() => {
                this.load = true
            }, 100)
        },
        methods: {
            saveSettingsPassword() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        axios.put(route('reset.password', this.$route.params.key), this.changePassword)
                            .then((response) => {
                                this.ressetPassText = 'Password was changed';
                                setTimeout(() => {
                                    $('#resetPassword').modal('hide');
                                    this.showAuth = !this.showAuth;
                                    this.$router.push('/login');
                                }, 2000)

                            })
                            .catch((err) => {
                                console.log(err)
                            })
                    }
                });

            }
        }
    }
</script>
