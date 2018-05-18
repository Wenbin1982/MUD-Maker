<template>
    <div>
        <header :class="[styleHeaderBg || !authPage ? 'bg_blue' : '']">
            <div class="wrap-center">
                <div class="wrap_logo">
                    <a href="/" class="logo"></a>
                    <span v-if="styleHeaderBg || !authPage">MUD File Maker</span>
                </div>
                <div v-if="!authPage">
                    <a href="/admin/users" target="_blank" class="btn btn_green btn_admin"
                       v-if="admin && !parseInt(header)">admin</a>

                    <nav v-if="parseInt(header)">
                        <ul>
                            <li>
                                <router-link to="/admin/users">Users</router-link>
                            </li>
                            <li>
                                <router-link to="/admin/domains">Domain</router-link>
                            </li>
                            <li>
                                <router-link to="/admin/files">Mud Files</router-link>
                            </li>
                        </ul>
                    </nav>

                    <div class="header_contact">
                        <span>Contact us at</span>
                        <a href="mailTo:mud-maker@external.cisco.com"> mud-maker@external.cisco.com</a>
                    </div>
                    <div class="wrap_logout">
                        <button class="btn" data-toggle="modal" data-target="#headerModal">{{data.name}}</button>
                        <a @click="logout" class="logout" style="color: #fff"><i class="fa fa-sign-out-alt"></i></a>
                    </div>
                </div>

            </div>

        </header>
        <div class="modal fade" id="headerModal" tabindex="-1" role="dialog"
             aria-labelledby="headerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>User settings:</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Name:</label>
                                <input type="text" placeholder="" v-model="data.name" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label>Email:</label>
                                <input type="email" name="email" placeholder="" v-model="data.email"
                                       class="form-control" v-validate="{ required: true, email: true }"
                                       :class="{'input': true, 'is-danger': errors.has('email') }">
                                <div v-show="errors.has('email')"
                                     class="help is-danger">
                                    {{ errors.first('email') }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Password:</label>
                                <input type="password"
                                       name="password"
                                       v-model="data.password"
                                       class="form-control"
                                       v-validate="{ required: true, min: 6 }"
                                       :class="{'input': true, 'is-danger': errors.has('password') }">

                                <div v-show="errors.has('password')" class="help is-danger">
                                    {{ errors.first('password') }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Confirm the password:</label>
                                <input type="password"
                                       name="confirm_password"
                                       data-vv-as="confirm password"
                                       v-model="data.confirm_password"
                                       class="form-control"
                                       v-validate="{ confirmed: 'password', required: true, min: 6 }"
                                       :class="{'input': true, 'is-danger': errors.has('confirm_password') }">

                                <div v-show="errors.has('confirm_password')"
                                     class="help is-danger">
                                    {{ errors.first('confirm_password') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary remove_btn"
                                @click='saveSettings'
                                :disabled="(data.email === user.email && data.name === user.name && data.password === user.password)">
                            Save settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="userRegistrationDomainModal" tabindex="-1" role="dialog"
             aria-labelledby="userRegistrationDomainModal" aria-hidden="true" data-backdrop="static"
             data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Registration domain:</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Domain:</label>
                                <input type="text" placeholder="" v-model="domainResponse" :readonly="true"
                                       class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label>Name company:</label>
                                <input type="email" placeholder="" v-model="registerCompany.company"
                                       class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary"
                                @click='saveCompany'
                                :disabled="!registerCompany.company">
                            Save
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

    export default {
        data: function () {
            return {
                registerCompany: {
                    domain: null,
                    company: null
                },
                data: {
                    name: null,
                    email: null,
                    password: null,
                    confirm_password: null
                },
                disabled: true,
            }
        },

        props: ['header', 'dataSource'],

        computed: {
            domainResponse() {
                return this.$store.state.domain
            },
            authPage() {
                return this.$store.state.authPage;
            },
            styleHeaderBg() {
                return this.$store.state.styleHeader
            },
            admin() {
                return this.$store.state.isAdmin
            },
            user() {
                this.data.name = this.$store.state.user.name;
                this.data.email = this.$store.state.user.email;
                this.data.password = this.$store.state.user.password;
                return this.$store.state.user
            }
        },
        created() {
            if (this.dataSource) {
                setTimeout(() => $('#headerModal').modal('show'), 0)
            }
            axios.get(route('admin.domains.show'))
                .then((response) => {
                    this.domainModal(response.data.userCompany);
                    this.registerCompany.domain = this.$store.state.domain;
                })
                .catch((err) => console.log(err))
        },
        methods: {
            ...mapActions([
                'styleHeader',
                'isAdmin',
                'domainModal'
            ]),
            saveCompany() {
                this.registerCompany.domain = this.$store.state.domain;
                axios.post(route('admin.domains.store'), this.registerCompany)
                    .then(() => {
                        $('#userRegistrationDomainModal').modal('hide');
                        setTimeout(() => {
                            this.$router.go({path: '/'})
                        }, 500);
                    });
            },
            saveSettings() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        axios.post(route('user.update'), this.data)
                            .then((response) => {
                                this.data.name = response.data.name;
                                this.data.email = response.data.email;
                                $('#headerModal').modal('hide');
                                if (this.data.password) {
                                    window.location.href = '/';
                                    this.isAdmin([{isAdmin: 0}]);
                                    this.styleHeader(false);
                                }
                            })
                            .catch((err) => {
                                console.log(err)
                            })
                    }
                });

            },
            saveSettingsDisabled() {
                (this.data.name === this.$store.state.user.name || this.data.name === this.$store.state.user.name) ? this.disabled = true : this.disabled = false;
            },
            logout() {
                axios.post(route('myLogout'))
                    .then((response) => {
                        axios.defaults.headers.common['X-CSRF-TOKEN'] = response.data;
                        this.$router.push({path: '/login'});
                        this.isAdmin([{isAdmin: 0}]);
                        this.styleHeader(false);
                        this.$store.state.authPage = true;
                    })
                    .catch((err) => console.log(err))
            }
        }
    }
</script>
