<template xmlns:v-on="http://www.w3.org/1999/xhtml">
    <div class="content form signup" :class="[ isLoading ? 'content-show' : 'content-hide' ]">
        <div class="text_info">  <b>Sign Up</b></div>
        <form class="yolo-signup-form yolo-enter-form" v-on:submit.prevent="handleRegistration"  autocomplete="off">
            <div :class="[errorClass.name ? 'error' : '', 'form-input']">
                <div class="new_input">
                    <label class="input__label input__label--nao" for="input-up-1">
                        <span class="input__label-content input__label-content--nao">Your name (e.g. John Doe)</span>
                    </label>
                    <span class="input input--nao">
                        <input class="yolo-signup-email-input input__field input__field--nao"
                               name="name"
                               @focus="focusError"
                               @blur="blur"
                               v-model="data.name"
                               type="text"
                               id="input-up-1"/>

                    </span>
                    <span class="error">{{this.customErrors.name}}</span>
                </div>
            </div>
            <div :class="[errorClass.email ? 'error' : '', 'form-input']">
                <div class="new_input">
                    <label class="input__label input__label--nao" for="input-up-2">
                        <span class="input__label-content input__label-content--nao">Your email</span>
                    </label>
                    <span class="input input--nao">
                        <input class="yolo-signup-email-input input__field input__field--nao"
                               name="email"
                               @focus="focusError"
                               @blur="blur"
                               v-model="data.email"
                               type="text"
                               id="input-up-2"/>

                    </span>
                    <span class="error">{{this.customErrors.email}}</span>
                </div>
            </div>

                <div :class="[errorClass.password ? 'error' : '', 'form-input']">
                        <div class="new_input">
                            <label class="input__label input__label--nao" for="input-up-3">
                                <span class="input__label-content input__label-content--nao">Password</span>
                            </label>
                            <span class="input input--nao">
                                <input class="yolo-signup-email-input input__field input__field--nao"
                                       name="password"
                                       @focus="focusError"
                                       @blur="blur"
                                       ref="password"
                                       v-model="data.password"
                                       type="password"
                                       id="input-up-3"/>

                            </span>
                            <span class="error">{{this.customErrors.password}}</span>
                        </div>
                </div>

                <div :class="[errorClass.password_confirmation ? 'error' : '', 'form-input']">
                    <div class="new_input">
                        <label class="input__label input__label--nao" for="input-up-4">
                            <span class="input__label-content input__label-content--nao">Repeat password</span>
                        </label>
                            <span class="input input--nao">
                                <input class="yolo-signup-email-input input__field input__field--nao"
                                       name="password_confirmation"
                                       @focus="focusError"
                                       @blur="blur"
                                       ref="password_confirmation"
                                       v-model="data.password_confirmation"
                                       type="password"
                                       id="input-up-4"/>

                            </span>
                        <span class="error">{{this.customErrors.password_confirmation}}</span>
                    </div>
                </div>

            <div class="wrap_check">
                <label class="control control--checkbox">
                    <input class="form-check-input" type="checkbox" v-model="checked" id="checkbox1"/>
                    <div class="control__indicator"></div>
                </label>
                <p>I agree with Terms of Use</p>
            </div>
            <div class="form-buttons">
                <div class="link_btn">
                    <a class="btn btn_blue yolo-anim-button to-login link" href="#" @click="showModal" next-modal="sign_in">You have account?</a>
                </div>
                <button type="submit" :disabled="!checked" class="btn btn_green yolo-enter-button yolo-ajax-signup yolo-anim-button to-signup-success button-big">Create new account</button>
            </div>
        </form>
    </div>

</template>

<script>
    import {EventBus, showModal} from '../../events.js';
    import axios from 'axios';
    let getDefaultProps = () => {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: ''
        };
    };
    export default {
        data: function () {
            return {
                name: "sign_up",
                isLoading: false,
                checked: false,
                customErrors: {},
                errorClass: {
                    name: false,
                    email: false,
                    password: false,
                    password_confirmation: false
                },
                previousErrorData: {},
                previousData: {},
                data: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                }
            }
        },
        mixins: [showModal],
        methods: {
            handleRegistration() {
                axios
                    .post(route('user.register'), this.data)
                    .then(({data}) => {
                        this.isLoading = false;
                        EventBus.$emit('modals:show_content', 'sing_up_success');
                        Object.assign(this.data, getDefaultProps());
                        $('input').each((index, input) => {
                            let currentInput = $(input);
                            if(currentInput.val()) currentInput.closest('.input--nao').removeClass('input--filled')
                        });
                        this.checked = false;
                    })
                    .catch(err => {
                        this.customErrors = err.response.data.errors;
                        for (let prop in this.customErrors) {
                            this.customErrors[prop] = this.customErrors[prop].join()
                        }
                        this.validateForm();
                    });
            }
        }
    }
</script>
