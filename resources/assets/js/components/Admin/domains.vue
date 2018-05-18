<template>
    <div class="container">

        <button class="btn btn_green" data-toggle="modal" data-target="#domains-modal">Create domain</button>
        <v-client-table :columns="columns" :data="data" :options="options">
            <a slot="id" slot-scope="props" :href="props.row.id"
               data-toggle="modal" data-target="#remove-domains-modal"
               @click="deleteRow($event, props.row.id)"><i class="fa fa-trash-alt"></i></a>
        </v-client-table>
        <div class="modal fade" id="domains-modal" tabindex="-1" role="dialog"
             aria-labelledby="domains-modal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Adding domain</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form v-on:submit.prevent="handleAddDomain" method="post">
                            <div class="form-group">
                                <label for="domain">Domain name *</label>
                                <input type="text" name="name" class="form-control" id="domain"
                                       placeholder="Enter domain name" v-model="list.domain">
                            </div>
                            <div class="form-group">
                                <label for="domain">Company name *</label>
                                <input type="text" name="name" class="form-control" id="company"
                                       placeholder="Enter company name" v-model="list.company">
                            </div>

                            <div class="modal-footer">
                                <a class="btn btn-default" data-dismiss="modal"><span
                                        class="glyphicon glyphicon-remove-circle"></span> Cancel</a>
                                <button class="btn btn-primary" type="submit"><span
                                        class="glyphicon glyphicon-check"></span> Submit
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="remove-domains-modal" tabindex="-1" role="dialog"
             aria-labelledby="domains-modal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Remove domain?</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>You are sure that you want to Remove Domain</h5>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-default" data-dismiss="modal"><span
                                class="glyphicon glyphicon-remove-circle"></span> Cancel</a>
                        <button class="btn btn-primary" @click="removeDomain" data-dismiss="modal" type="submit"><span
                                class="glyphicon glyphicon-check"></span> Yes
                        </button>
                    </div>

                </div>
            </div>
        </div>
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
                rowId: null,
                list: {
                    domain: '',
                    company: ''
                },
                data: [],
                columns: ['company', 'domain', 'name', 'id'],
                options: {
                    columnsClasses: {
                        id: 'domain_delete_column'
                    },
                    headings: {
                        company: 'company',
                        domain: 'domain',
                        name: 'Name',
                        id: 'Delete'
                    },
                    sortable: ['company','domain', 'name'],
                    filterable: ['company', 'domain', 'name'],
                }
            }
        },

        created() {
            this.axiosConstructor(axios.get(route('admin.domains.show')));
        },
        mixins: [storeShowButtonIsAdmin],
        methods: {
            ...mapActions([
                'isAdmin'
            ]),
            axiosConstructor(value) {
                value.then((response) => {
                    this.data = response.data.domains;
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
            deleteRow(event, id) {
                event.preventDefault();
                this.rowId = id;
            },
            removeDomain() {
                this.axiosConstructor(axios.delete(route('admin.domains.delete', this.rowId)));
            },
            handleAddDomain() {
                axios
                    .post(route('admin.domains.store'), this.list)
                    .then((data) => {
                        this.data.push(Object.assign({}, data.data, {name:data.data.user.name}));
                        $('#domains-modal').modal('toggle');
                    })
                    .catch(err => {
                        console.log('err', err);
                    });
            }
        }
    }
</script>
