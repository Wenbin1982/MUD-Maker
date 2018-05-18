import Vue from 'vue';

export const EventBus = new Vue();
import axios from "axios";

export const storeShowButtonIsAdmin = {
    computed: {
        authPage() {
            return this.$store.state.authPage;
        },
    },

    created() {
        this.$store.state.authPage = false;
        axios.get(route('isAdmin.show'))
            .then((response) => {
                this.isAdmin(response.data);
                this.styleHeader = true;
            })
            .catch((err) => {
                console.log(err)
            })
    }

};

export const storeDefault = {
    data: function () {
        return {
            state: {
                random: Math.floor(10000 + Math.random() * 90000),
                showNetwork: {
                    internetCommunication: {
                        show: false,
                        name: 'cl'
                    },
                    controllersSpecific: {
                        show: false,
                        name: 'myctl'
                    },
                    controllerAccess: {
                        show: false,
                        name: 'ent'
                    },
                    localCommunication: {
                        show: false,
                        name: 'loc'
                    },
                    specificType: {
                        show: false,
                        name: 'man'
                    },
                    device: {
                        show: false,
                        name: 'myman'
                    }
                },
                mudhost: '',
                mudfilename: '',
                sysDescr: '',
                internetCommunication: [{
                    name: {value: ""},
                    portl: {value: "any"},
                    port: {value: "any"},
                    initiatedBy: {
                        selected: {text: 'Either', value: 'either'},
                        options: [
                            {text: 'Either', value: 'either'},
                            {text: 'Thing', value: 'thing'},
                            {text: 'Remote', value: 'remote'}
                        ]
                    },
                    protocolSelect: {
                        selected: {text: 'Any', value: 'any'},
                        options: [
                            {text: 'Any', value: 'any'},
                            {text: 'TCP', value: 'tcp'},
                            {text: 'UDP', value: 'udp'}
                        ]
                    }
                }],
                controllerAccess: [{
                    name: {value: ""},
                    portl: {value: "any"},
                    port: {value: "any"},
                    initiatedBy: {
                        selected: {text: 'Either', value: 'either'},
                        options: [
                            {text: 'Either', value: 'either'},
                            {text: 'Thing', value: 'thing'},
                            {text: 'Remote', value: 'remote'}
                        ]
                    },
                    protocolSelect: {
                        selected: {text: 'Any', value: 'any'},
                        options: [
                            {text: 'Any', value: 'any'},
                            {text: 'TCP', value: 'tcp'},
                            {text: 'UDP', value: 'udp'}
                        ]
                    }
                }],
                controllersSpecific: [{
                    name: {value: "(filled in by local admin)"},
                    portl: {value: "any"},
                    port: {value: "any"},
                    initiatedBy: {
                        selected: {text: 'Either', value: 'either'},
                        options: [
                            {text: 'Either', value: 'either'},
                            {text: 'Thing', value: 'thing'},
                            {text: 'Remote', value: 'remote'}
                        ]
                    },
                    protocolSelect: {
                        selected: {text: 'Any', value: 'any'},
                        options: [
                            {text: 'Any', value: 'any'},
                            {text: 'TCP', value: 'tcp'},
                            {text: 'UDP', value: 'udp'}
                        ]
                    }
                }],
                localCommunication: [
                    {
                        name: {value: "any"},
                        portl: {value: "any"},
                        port: {value: "any"},
                        initiatedBy: {
                            selected: {text: 'Either', value: 'either'},
                            options: [
                                {text: 'Either', value: 'either'},
                                {text: 'Thing', value: 'thing'},
                                {text: 'Remote', value: 'remote'}
                            ]
                        },
                        protocolSelect: {
                            selected: {text: 'Any', value: 'any'},
                            options: [
                                {text: 'Any', value: 'any'},
                                {text: 'TCP', value: 'tcp'},
                                {text: 'UDP', value: 'udp'}
                            ]
                        }
                    }
                ],
                specificType: [
                    {
                        name: {value: ""},
                        portl: {value: "any"},
                        port: {value: "any"},
                        initiatedBy: {
                            selected: {text: 'Either', value: 'either'},
                            options: [
                                {text: 'Either', value: 'either'},
                                {text: 'Thing', value: 'thing'},
                                {text: 'Remote', value: 'remote'}
                            ]
                        },
                        protocolSelect: {
                            selected: {text: 'Any', value: 'any'},
                            options: [
                                {text: 'Any', value: 'any'},
                                {text: 'TCP', value: 'tcp'},
                                {text: 'UDP', value: 'udp'}
                            ]
                        }
                    }
                ],
                device: [
                    {
                        name: {value: "(filled in by system)"},
                        portl: {value: "any"},
                        port: {value: "any"},
                        initiatedBy: {
                            selected: {text: 'Either', value: 'either'},
                            options: [
                                {text: 'Either', value: 'either'},
                                {text: 'Thing', value: 'thing'},
                                {text: 'Remote', value: 'remote'}
                            ]
                        },
                        protocolSelect: {
                            selected: {text: 'Any', value: 'any'},
                            options: [
                                {text: 'Any', value: 'any'},
                                {text: 'TCP', value: 'tcp'},
                                {text: 'UDP', value: 'udp'}
                            ]
                        }
                    }
                ],
                deviceSelect: {
                    selected: {
                        text: 'IPv4', value: 'v4'
                    },
                    options: [
                        {text: 'IPv4', value: 'v4'},
                        {text: 'IPv6', value: 'v6'},
                        {
                            text: 'Both', value: 'both'
                        }
                    ]
                }
            }
        }
    }
};

export const dataJson = {
    mounted() {
        EventBus.$on('handleJson', (data) => {
            this.json = data;
        });
    }
};

export const showModal = {
    mounted() {

        EventBus.$on('modals:show_content', name => {
            this.isLoading = name === this.name;
        });
    },
    methods: {
        changeTypePass(attribute, type) {
            // if (attribute === 'password') {
            //     this.$refs.password.setAttribute('type', type);
            // }
            // if (attribute === 'password_confirmation') {
            //     this.$refs.password_confirmation.setAttribute('type', type);
            // }
        },
        changeData(dataNew, addErrorClass, key) {
            console.log(dataNew[key]);
            this.errorClass[key] = addErrorClass;
        },
        setError(bool, target) {
            const data = bool ? this.errors : this.previousErrorData;
            const type = bool ? 'text' : 'password';

            if (this.errors[target]) {
                this.changeData(data, bool, target);
                this.changeTypePass(target, type);
            }
        },
        blur(e) {
            const elem = e.target.getAttribute('name');
            this.toggleClass(e);
            if (!$(e.target).val()) {
                this.setError(true, elem);
            } else {
                this.setError(false, elem);
            }
        },
        toggleClass(e) {
            if (!$(e.target).val()) e.target.parentNode.className = 'input input--nao';
        },
        focusError(e) {
            const elem = e.target.getAttribute('name');
            if (this.errors[elem]) {
                this.setError(false, elem);
            }
            e.target.parentNode.className = 'input input--nao input--filled';
        },
        validateForm() {
            for (let key in this.data) {
                this.setError(true, key);
            }
        },
        showModal: e => {
            let type = e.target.getAttribute('next-modal');
            e.preventDefault();
            this.isLoading = false;
            EventBus.$emit('modals:show_content', type);
        }
    }
};