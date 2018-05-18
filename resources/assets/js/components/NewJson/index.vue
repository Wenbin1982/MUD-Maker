<template>
    <div class="container">
        <div class="info_top">
            <i class="fas fa-info-circle"></i>
            <p>This page will help you <b>create a Manufacturer Usage Description (MUD) file</b> for your web site. MUD
                files can be used by local networks to determine how they should protect your products. You should
                only list communications on this page that you have designed your product to have. For more
                information, see <a href="https://datatracker.ietf.org/doc/draft-ietf-opsawg-mud/">draft-ietf-opsawg-mud</a>.</p>
        </div>
        <mud-form :product="product" :newMudFile="newMudFile" :resetStyle="resetStyle" :mudRoute="mudRoute" v-bind="{reset}"></mud-form>
    </div>
</template>

<script>
    import axios from 'axios';
    import {mapGetters, mapActions} from 'vuex';
    import JsonTree from 'vue-json-tree/dist/json-tree'
    import {storeDefault, storeShowButtonIsAdmin} from '../../events.js';
    import mudForm from '../MudForm/index.vue';
    import _ from 'lodash';

    export default {
        data: function () {
            return {
                title: {},
                manufacturer: null,
                jsonView: null,
                mudRoute: 'store',
                newMudFile: false,
                resetStyle : {
                    display:'inline-block'
                }
            }
        },
        components: {
            'mud-form': mudForm
        },
        computed: {

            product() {
                return this.$store.state.product
            },
            ...mapGetters([
                'dinamicName',
                'jsonClnames',
                'jsonCreate'
            ])
        },
        created() {
            axios
                .get(route('create.show'))
                .then(({data}) => {
                    this.$store.state.mudUrl = data.data.url;
                    this.$store.state.mudName = data.data.name_mud_file;
                    this.$store.state.product.mudhost = data.data.manufacturer;
                })
                .catch(err => {
                    console.error(err);
                });
        },
        mounted() {
            this.$nextTick(() => {
                this.$store.state.product = _.cloneDeep(this.state);
            })
        },
        mixins: [storeDefault, storeShowButtonIsAdmin],
        methods: {
            ...mapActions([
                'isAdmin',
                'createJson',
                'removeInput',
                'addInput'
            ]),
            reset() {
                this.$store.state.product = _.cloneDeep(this.state);
                $('.card').removeClass('active');
                $('.collapse.show').collapse('hide');
            },
            clnamesJson() {
                this.$store.commit('clnamesJson');
            }
        }
    }
</script>
