<template>
    <div class="container">
        <div class="title_top">
            <h2>Welcome to MUD File Maker!</h2>

            <p>This page will help you create a Manufacturer Usage Description (MUD) file for your web site. MUD
                files
                can be used by local networks to determine how they should protect your products. You should only
                list
                communications on this page that you have designed your product to have. For more information, see
                draft-ietf-opsawg-mud.</p>
        </div>

        <mud-form :product="product" :newMudFile="newMudFile" :resetStyle="resetStyle" :mudRoute="mudRoute"></mud-form>

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
                newMudFile: true,
                mudRoute: 'update',
                jsonView: null,
                resetStyle: {
                    display: 'none'
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
                'jsonCreate',
                'default_product'
            ])
        },

        mixins: [storeDefault, storeShowButtonIsAdmin],
        created() {
            this.$store.state.product = this.state;
            axios
                .get(route('editJson', this.$route.params.id))
                .then(({data}) => {
                    let product = this.$store.state.product;

                    this.$store.state.mudName = data.nameMudFile.split(".json")[0].split("/").pop();
                    this.$store.state.mudUrl = data.url;
                    product.deviceSelect.selected.value = data.deviceSelect;
                    product.deviceSelect.selected.text = data.deviceSelect.charAt(0).toUpperCase() + data.deviceSelect.slice(1);
                    product.mudfilename = data.model;
                    product.mudhost = data.manufacturer;
                    product.deviceType = data.deviceType;
                    product.sysDescr = data.software;

                    for (let i in product) {

                        if (Array.isArray(data[i]) && data[i].length > 0) {
                            product.showNetwork[i].show = true;
                            product[i] = data[i].map(item => {
                                //try to use const arr =  Object.assign({}, JSON.parse(JSON.stringify(...this.state[i])));
                                //or use lodash
                                const arr = _.cloneDeep(...this.state[i]);

                                arr.name.value = item['name'];
                                arr.port.value = item['port'];
                                arr.portl.value = item['portl'];
                                arr.initiatedBy.selected.value = item['initiatedBy'];
                                arr.initiatedBy.selected.text = item['initiatedBy'];
                                arr.protocolSelect.selected.value = item['protocolSelect'];
                                arr.protocolSelect.selected.text = item['protocolSelect'].charAt(0).toUpperCase() + item['protocolSelect'].slice(1);

                                return arr;
                            });
                        }
                    }
                })
                .catch(err => {

                });
        },

        methods: {
            ...mapActions([
                'isAdmin',
                'createJson',
                'removeInput',
                'addInput'
            ]),
            accordion(event) {
                let inp = $(event.target).closest('.card');
                $(event.target).attr('type') === "checkbox" ? inp.addClass('active').find('.collapse').collapse('show') : inp.toggleClass('active').find('.collapse').collapse('toggle');
            },
            clnamesJson() {
                this.$store.commit('clnamesJson');
            },
        }
    }
</script>
