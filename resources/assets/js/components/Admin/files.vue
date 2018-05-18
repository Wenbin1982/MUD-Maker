<template>
    <div class="container">
        <v-client-table :columns="columns" :data="data" :options="options"></v-client-table>
    </div>
</template>

<script>
    import axios from 'axios';
    import {storeShowButtonIsAdmin} from '../../events.js';
    import {mapGetters, mapActions} from 'vuex';
    import _ from 'lodash';

    export default {
        data: function () {
            return {

                data: [],
                columns: ['name','mudhost', 'mudfilename', 'deviceType',  'sysDescr', 'created_at'],
                options: {

                    headings: {
                        name: 'Name',
                        mudhost: 'Manufacturer',
                        mudfilename: 'Model',
                        deviceType: 'Device type',
                        sysDescr: 'Software version',
                        created_at: 'Create Date',
                    },
                    sortable: ['name','mudhost', 'mudfilename', 'deviceType',  'sysDescr', 'created_at'],
                    filterable: ['name','mudhost', 'mudfilename', 'deviceType',  'sysDescr', 'created_at'],
                }
            }
        },

        created() {
            this.axiosConstructor(axios.get(route('admin.mudFiles')));
        },
        mixins: [storeShowButtonIsAdmin],
        methods: {
            ...mapActions([
                'isAdmin'
            ]),
            axiosConstructor(value) {
                value.then((response) => {
                    this.data = response.data;
                    this.data.map((item, index) => {
                        if(item.user) {
                            this.data[index].name = item.user.name
                        }
                    });

                })

                    .catch((err) => {
                        console.log(err)
                    })
            },

        }
    }
</script>
