<template>
    <div class="container">
        <div class="title title_view"><h2>Your MUD file is ready!</h2>
            <p>Congratulations! You've just created your first MUD file. Simply Cut and paste beween the lines and
                stick
                into a file. Your next steps are to sign the file and place it in the location that its
                corresponding
                MUD URL will find.</p></div>

        <div class="row-buttons">
            <h4>Would you like to download this file?</h4>
            <div class="wrap-btn">
                <button class="btn btn_green" @click="downloadJson">Download <i class="fa fa-download"></i></button>
                <button class="btn btn_blue copyLink" :data-clipboard-text="copyLink()" @click="copied($event)">
                    <div class="copy_tool">Copied!</div>
                    Copy link file
                    <i class="fas fa-link"></i>

                </button>
            </div>
        </div>
        <json-tree :raw="jsonView"></json-tree>
    </div>
</template>

<script>
    import JsonTree from 'vue-json-tree/dist/json-tree'
    import {storeShowButtonIsAdmin} from '../../events.js';
    import {mapGetters, mapActions} from 'vuex';
    import axios from 'axios';
    import ClipboardJS from 'clipboard';

    export default {
        data: function () {
            return {
                jsonView: null,
                jsonName: null,
                linkFile: null,
                clipboard: new ClipboardJS('.copyLink'),
                data: {
                    jsonId: this.$route.params.userId,
                }
            }
        },
        mixins: [storeShowButtonIsAdmin],
        created() {
            axios
                .post(route('showJson'), this.data)
                .then(({data}) => {
                    this.linkFile = data.link_file + data.name;
                    this.jsonView = data.json;
                    this.jsonName = data.name;
                })
                .catch((err) => {
                    console.log(err)
                });

        },
        methods: {
            ...mapActions([
                'isAdmin'
            ]),
            copied(event) {
                let el = $(event.target).find('.copy_tool');
                el.addClass('active');
                setTimeout(() => {
                    el.removeClass('active')
                }, 1000)
            },
            copyLink() {
                return this.linkFile;
            },
            downloadJson() {
                axios.post(route('downloadJson'), this.data, {responseType: 'blob'})
                    .then((response) => {
                        let a = document.createElement('a');
                        a.style = "display: none";
                        let blob = new Blob([response.data], {type: 'text/json'});
                        let url = window.URL.createObjectURL(blob);
                        a.href = url;
                        a.download = this.jsonName;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);
                    })
                    .catch((err) => {
                        console.log(err)
                    })

            }
        }
    }
</script>
