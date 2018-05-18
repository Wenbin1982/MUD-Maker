<template>
    <div class="content form forgot_pass" :class="[ isLoading ? 'content-show' : 'content-hide' ]">
        <div class="text_info"><b>Forgot Password?</b></div>
        <form class="yolo-forgot-password-form yolo-enter-form" v-on:submit.prevent="handleLogin">
            <div :class="[errorClass.email ? 'error' : '', 'form-input']">
                <div class="new_input">
                    <label class="input__label input__label--nao" for="input-1">
                        <span class="input__label-content input__label-content--nao">Your email</span>
                    </label>
                    <span class="input input--nao">
                        <input class="input__field input__field--nao"
                               name="email"
                               type="text"
                               id="input-1"
                               @click="focusError"
                               @blur="blur"
                               v-model="data.email"
                               autocomplete="nope"/>

                    </span>
                    <span class="error">{{customErrors.email}}</span>
                    <span class="error">{{errorPassReset}}</span>
                </div>
            </div>
            <div class="form-buttons">
                <div class="link_btn"><a class="btn btn_blue yolo-anim-button to-main link" href="#" @click="showModal"
                                         next-modal="sign_in">Cancel</a></div>
                <button type="submit"
                        class="btn btn_green yolo-enter-button yolo-ajax-forgot-password yolo-anim-button to-forgot-success button-big">
                    Submit
                </button>
            </div>
        </form>

    </div>
</template>

<script>
    import {EventBus, showModal} from '../../events.js';

    export default {
        data: function () {
            return {
                name: "forgot_pass",
                isLoading: false,
                customErrors: {},
                errorPassReset: null,
                errorClass: {
                    email: false,
                },
                previousErrorData: {},
                previousData: {},
                data: {
                    email: '',
                }
            }
        },
        mixins: [showModal],
        methods: {

            handleLogin() {

                axios
                    .post(route('api.resetPassword'), this.data)
                    .then(({data}) => {
                        EventBus.$emit('modals:show_content', 'forgot_pass_success');
                    })
                    .catch(err => {
                        if (err.response.data.message) {
                            return this.errorPassReset = err.response.data.message;
                        }
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
