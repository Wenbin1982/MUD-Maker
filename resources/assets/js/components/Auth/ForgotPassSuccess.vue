<template>
    <div class="content form forgot_pass_success" :class="[ isLoading ? 'content-show' : 'content-hide' ]">
        <h1>Password Reset</h1>
        <p class="slogan">
            A Reset Password link will be sent to you via email
            in a few minutes. If you do not see it in your Inbox,
            please check your Spam folder.
        </p>
        <div class="form-buttons">
            <a class="btn btn_green yolo-anim-button to-main button-big" @click="showModal" next-modal="sign_in">BACK</a>
        </div>
    </div>
</template>

<script>
    import { EventBus, showModal } from '../../events.js';
    export default {
        data: function () {
            return {
                name: "forgot_pass_success",
                isLoading: false,
            }
        },
        mixins: [showModal],
        methods: {

        }
    }
</script>
