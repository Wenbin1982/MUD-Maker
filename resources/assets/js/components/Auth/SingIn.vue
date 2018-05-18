<template>
    <div class="content form login" :class="[ isLoading ? 'content-show' : 'content-hide' ]">

        <div class="text_info">
            <b>Sign in</b>
        </div>
        <form class="yolo-login-form yolo-enter-form" v-on:submit.prevent="handleLogin" autocomplete="off">

            <div :class="[errorClass.email ? 'error' : '', 'form-input']">
                <div class="new_input">
                    <label class="input__label " for="input-in-1">
                        <span class="input__label-content ">Your email</span>
                    </label>
                    <span class="input ">
                        <input class="input__field "
                               name="email"
                               type="text"
                               @focus="focusError"
                               @blur="blur"
                               v-model="data.email"
                               id="input-in-1"
                               autocomplete="nope"/>

                    </span>
                    <span class="error">{{this.customErrors.email}}</span>
                </div>
            </div>
            <div :class="[errorClass.password ? 'error' : '', 'form-input']">
                <div class="new_input">
                    <label class="input__label" for="input-in-2">
                        <span class="input__label-content ">Password</span>
                    </label>

                    <span class="input ">
                        <input class="input__field "
                               name="password"
                               type="password"
                               id="input-in-2"
                               ref="password"
                               @focus="focusError"
                               autocomplete="new-password"
                               v-model="data.password"/>

                    </span>
                    <span class="error">{{this.customErrors.password}}</span>
                    <span class="error">{{err}}</span>
                    <a class="yolo-anim-button to-forgot-pass forgot_pass_btn" href="#" @click="showModal"
                       next-modal="forgot_pass">Forgot Password?</a>
                </div>
            </div>
            <div class="form-buttons">
                <div class="link_btn">
                    <a class="btn btn_blue yolo-anim-button to-signup button-big" @click="showModal"
                       next-modal="sign_up">Create
                        account</a>
                </div>
                <button type="submit" class="btn btn_green button-big yolo-enter-button">Log in</button>
            </div>
        </form>
    </div>
</template>

<script>
    import {EventBus, showModal} from '../../events.js';
    import {mapGetters, mapActions} from 'vuex';
    import axios from 'axios';

    export default {
        data: function () {
            return {
                name: "sign_in",
                isLoading: true,
                customErrors: {},
                err: null,
                load: false,
                errorClass: {
                    email: false,
                    password: false,
                },
                previousErrorData: {},
                previousData: {},
                data: {
                    email: '',
                    password: '',
                    remember: ''
                }
            }
        },
        computed: {
            modalText() {
                return this.$store.state.modalText
            },
        },
        mixins: [showModal],
        methods: {
            ...mapActions([
                'isAdmin',
                'domainModal'
            ]),

            handleLogin() {
                axios
                    .post(route('myLogin'), this.data)
                    .then(({data}) => {
                        setTimeout(() => {this.domainModal(data.user);}, 1000);

                        this.isAdmin(data.user);
                        if (data.user === '') {
                            this.isLoading = false;
                            EventBus.$emit('modals:show_content', 'sing_in_wait_activation');
                        } else {
                            this.isLoading = false;
                            this.$router.push({path: '/'})
                        }
                    })
                    .catch(err => {
                        this.customErrors = {};
                        this.err = null;
                        if (err.response.data.errors) {
                            this.customErrors = err.response.data.errors;
                            for (let prop in this.customErrors) {
                                this.customErrors[prop] = this.customErrors[prop].join()
                            }
                            this.validateForm();
                        } else {
                            this.err = err.response.data.error;
                        }
                        if (err.response.data.noConfirm) {
                            this.$store.state.modalText = 'Your account has been successfully created. Please wait for activation.';
                            $('#authModal').modal('show');
                        }
                        if (err.response.data.changePass) {
                            this.$store.state.modalText = 'We have e-mailed your password reset link!';
                            $('#authModal').modal('show');
                        }
                    });
            }
        }
    }
</script>
