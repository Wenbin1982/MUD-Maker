<template>
    <div class="content form signup_success" :class="[ isLoading ? 'content-show' : 'content-hide' ]">
        <p class="slogan">Your account has been successfully created. Please wait for activation.
        Use of this tool is restricted to Cisco employees and authorized Cisco reseller partners.]
        Please check to make sure you used your work e-mail address.
        <a href="">https://mud-maker.cisco.com</a></p>
        <div class="form-buttons">
            <a class="yolo-anim-button to-main button-big" @click="showModal" next-modal="sign_in">BACK</a>
        </div>
    </div>
</template>

<script>
    import { EventBus, showModal } from '../../events.js';
    import axios from 'axios';

    export default {
        data: function () {
            return {
                name: "sing_in_wait_activation",
                isLoading: false,
            }
        },
        mixins: [showModal],
    }
</script>
