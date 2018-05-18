<template>
    <div class="container">
        <v-client-table :columns="columns" :data="data" :options="options"></v-client-table>
    </div>
</template>

<script>
    import axios from 'axios';
    import {storeShowButtonIsAdmin} from '../../events.js';
    import {mapGetters, mapActions} from 'vuex';
    export default {
        data: function () {
            return {
                data: [],
                columns: ['name', 'email'],
                options: {
                    headings: {
                        name: 'Name',
                        email: 'Email',
                    },
                    sortable:['name', 'email'],
                    filterable:['name', 'email'],
                }
            }
        },
        mixins: [storeShowButtonIsAdmin],
        created() {
            axios.get(route('admin.users.show'))
                .then((response) => {
                   this.data = response.data;
                    this.styleHeader(false);
                })
                .catch((err) => {
                    console.log(err)
                })
        },
        methods: {
            ...mapActions([
                'isAdmin'
            ]),
        }
    }
</script>
