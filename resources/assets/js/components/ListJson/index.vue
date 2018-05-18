<template>
    <div class="container">

        <transition name="fade" mode="out-in">
            <div class="wrap_table" key="1" v-if="!showTables">
                <div class="title_table">
                    <h2>MUD list</h2>
                    <div class="wrap_btn">
                        <button class="btn btn_blue" :disabled="disabled" @click="showTable">Show Delete List</button>
                        <router-link to="/create" class="btn btn_green">Create New</router-link>
                    </div>
                </div>
                <v-client-table :columns="columns" :data="listJson" :options="options">
                    <a slot="id" slot-scope="props" :href="props.row.id" class="table_link"
                       @click='toolTip($event, props.row.id)'>

                        <i class="fa fa-ellipsis-h"></i>
                        <div class="tooltip_item" @mouseleave="leave($event)">
                            <ul>
                                <li class="arrow"><i class="fa fa-sort-up"></i></li>
                                <li @click='show($event)'><i class="fas fa-eye"></i>Prewiew</li>
                                <li @click='edit($event)'><i class="fas fa-pencil-alt"></i>Edit</li>
                                <li @click="removeRow('remove')"
                                    data-toggle="modal" data-target="#deleteAndRestoreModal"><i
                                        class="fas fa-trash"></i>Delete
                                </li>
                                <li class="copyLink" :data-clipboard-text="copyLink(props.row.id)"
                                    @click="copied($event)"><i class="fas fa-link"></i>
                                    <div class="copy_tool">Copied!</div>
                                    <input type="text"
                                           style="opacity: 0; visibility: hidden; position: absolute; z-index: -111;">
                                    Copy link
                                </li>
                                <li @click="download(props.row.id, props.row.nameMudFile)">
                                    <i class="fa fa-download"></i>Download
                                </li>
                                <li class="copyLink" @click="cloneMudFile($event, props.row.id)">
                                    <div class="copy_tool">Cloned file!</div>
                                    <i class="fa fa-clone"></i>Clone file
                                </li>
                            </ul>
                        </div>
                    </a>
                </v-client-table>
            </div>
            <div class="wrap_table" key="2" v-if="showTables">
                <div>
                    <div class="title_table">
                        <h2>MUD remove Files</h2>
                        <div class="wrap_btn">
                            <button class="btn btn_blue" @click="showTable">Back to MUD List</button>
                            <button @click="restoreFile('restore')" data-toggle="modal"
                                    data-target="#deleteAndRestoreModal" :disabled="!checkedNames.length"
                                    class="btn btn_green">Restore
                            </button>
                        </div>
                    </div>
                    <v-client-table :columns="columns_delete" :data="deleteFies" :options="optionsDelete"
                                    class="table_danger">
                        <a slot="restore" slot-scope="props" :href="props.row.id">
                            <label class="control control--checkbox">
                                <input class="form-check-input" type="checkbox" :value="props.row.id"
                                       v-model="checkedNames">
                                <div class="control__indicator"></div>
                            </label>

                        </a>
                    </v-client-table>
                </div>
            </div>
        </transition>
        <pagination :per-page="10" :records="0"></pagination>
        <div class="modal fade" id="deleteAndRestoreModal" tabindex="-1" role="dialog"
             aria-labelledby="removeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>{{modalText}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>{{modalBodyText}}</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary remove_btn" data-dismiss="modal"
                                @click='removeAndRestoreRow'>Yes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import {mapGetters, mapActions} from 'vuex';
    import {storeDefault, storeShowButtonIsAdmin} from '../../events.js';
    import ClipboardJS from 'clipboard';
    import _ from 'lodash';

    export default {
        data: function () {
            return {
                users: [],
                data: {
                    jsonId: null,
                },
                url: null,
                showTables: false,
                disabled: true,
                deleteFies: [],
                responseModal: null,
                toolId: null,
                modalBodyText: null,
                modalText: null,
                clipboard: new ClipboardJS('.copyLink'),
                checkedNames: [],
                listJson: [],
                rowIdState: null,
                columns_delete: ['name', 'delete_name_user', 'manufacturer', 'model', 'deviceType', 'software', 'created_at', 'restore'],
                columns: ['name', 'last_update_name', 'manufacturer', 'model', 'deviceType', 'software', 'created_at', 'id'],
                optionsDelete: {
                    headings: {
                        delete_name_user: 'Deleted by'
                    },
                    sortable: ['name', 'delete_name_user', 'manufacturer', 'model', 'deviceType', 'software', 'created_at'],
                },
                options: {
                    headings: {
                        name: 'Name',
                        last_update_name: 'Updated by',
                        manufacturer: 'Manufacturer',
                        model: 'Model',
                        deviceType: 'Device type',
                        software: 'Software version',
                        created_at: 'Create Date',
                        show: 'Preview',
                        edit: 'Edit',
                        id: 'Act',
                        path: 'Path'
                    },
                    sortable: ['name', 'last_update_name', 'manufacturer', 'model', 'deviceType', 'software', 'created_at'],
                    filterable: ['name', 'last_update_name', 'manufacturer', 'model', 'deviceType', 'software', 'created_at']
                }
            }
        },
        mixins: [storeDefault, storeShowButtonIsAdmin],

        created() {
            axios
                .get(route('main'))
                .then(({data}) => {
                    this.listJson = data.data.reverse();
                    this.deleteFies = data.deleteFiles;
                    this.deleteFies.length ? this.disabled = false : this.disabled = true;
                    this.url = data.url;
                });
            this.clipboard.on('success', (e) => {
                e.clearSelection();
            });
        },
        methods: {
            ...mapActions([
                'isAdmin'
            ]),
            download(id, name) {
                this.data.jsonId = id;
                axios.post(route('downloadJson'), this.data, {responseType: 'blob'})
                    .then((response) => {
                        let a = document.createElement('a');
                        a.style = "display: none";
                        let blob = new Blob([response.data], {type: 'text/json'});
                        let url = window.URL.createObjectURL(blob);
                        a.href = url;
                        a.download = name;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);
                    })
                    .catch((err) => {
                        console.log(err)
                    })
            },
            copied(event) {
                let el = $(event.target).find('.copy_tool');
                el.addClass('active');
                setTimeout(() => {
                    el.removeClass('active')
                }, 1000)
            },
            copyLink(id) {
                let link = _.find(this.listJson, item => {
                    return item.id === id
                });
                return `${this.url}/mudFile/${link.path}${link.nameMudFile}`;
            },
            leave(event) {
                $(event.target).removeClass('active');
            },
            toolTip(event, fileId) {
                event.preventDefault();
                this.toolId = fileId;
                $(event.target.parentElement).find('.tooltip_item').addClass('active');
            },
            removeRow(value) {
                this.rowIdState = this.toolId;
                this.modalText = 'Remove mud file?';
                this.modalBodyText = `You are sure that you want to Remove MUD File`;
                this.responseModal = value;
            },
            restoreFile(value) {
                this.modalText = 'Restore MUD files?';
                this.modalBodyText = `You are sure that you want to Restore ${this.checkedNames.length} MUD Files`;
                this.responseModal = value;
            },
            axiosConstructor(value, id) {
                axios
                    .post(value, id)
                    .then(({data}) => {
                        this.listJson = data.data.reverse();
                        this.deleteFies = data.deleteFiles;
                        this.deleteFies.length ? this.disabled = false : this.disabled = true;
                        this.checkedNames = [];
                    });
            },
            removeAndRestoreRow() {
                (this.responseModal === 'remove') ? this.axiosConstructor(route('deleteJson', this.rowIdState)) : this.axiosConstructor(route('restore'), {id: this.checkedNames});
            },
            showTable() {
                this.showTables = !this.showTables
            },
            show(event) {
                event.preventDefault();
                this.$router.push({
                    path: `/show/${this.toolId}`
                });
            },
            edit(event) {
                event.preventDefault();
                this.$router.push({
                    path: `/edit/${this.toolId}`,
                });
            },
            cloneMudFile(event, id) {
                this.copied(event);
                axios.get(route('clone.MudFile', id))
                    .then((response) => {
                        this.listJson = response.data.data.reverse();
                        this.deleteFies = response.data.deleteFiles;
                        this.deleteFies.length ? this.disabled = false : this.disabled = true;
                        $('.VueTables__table').addClass('clone_row');
                        setTimeout(() => {  $('.VueTables__table').removeClass('clone_row');}, 1500);
                    })
                    .catch((err) => {
                        console.log(err)
                    })
            }
        }
    }
</script>
