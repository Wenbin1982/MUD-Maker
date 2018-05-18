<template xmlns:v-on="http://www.w3.org/1999/xhtml">
    <div class="content form signup_success" :class="[ isLoading ? 'content-show' : 'content-hide' ]">
        <h1>Thank You!</h1>
        <p class="slogan">
            To complete the process please check your email.
            Within the email you will find a link which you must
            click in order to activate your account.
        </p>
        <div class="form-buttons">
            <a class="btn btn_green" @click="showModal" next-modal="sign_in">BACK</a>
        </div>
    </div>

</template>

<script>
    import {EventBus, showModal} from '../../events.js';
    import axios from 'axios';

    export default {
        data: function () {
            return {
                name: "sing_up_success",
                isLoading: false,
                checked: false,
                errorClass: {
                    name: false,
                    email: false,
                    password: false,
                    password_confirmation: false
                },
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

        }
    }
</script>
