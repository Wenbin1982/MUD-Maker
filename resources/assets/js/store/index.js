import Vue from 'vue';
import Vuex from 'vuex';
import _ from 'lodash';

Vue.use(Vuex);
export const store = new Vuex.Store({

    state: {
        mudUrl: null,
        mudName: null,
        domain: null,
        user: [],
        modalText: null,
        authPage: true,
        styleHeader: false,
        json: {},
        isAdmin: 0,
        product: {
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
            deviceType: '',
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
    },
    getters: {

        udpTcpIetfMud: () => (controller, nameController, version, protocol) => {
            return {
                "ietf-mud:mud": {
                    [controller]: nameController
                },
                [version]: {
                    "protocol": protocol
                },
            }
        },
        anyIetfMud: () => (controller, nameController) => {
            return {
                "ietf-mud:mud": {
                    [controller]: nameController
                }
            }
        },
        ace: (state, getters) => (state, index, version, name, use, device, aceName, title) => {
            let productLine = device[index],
                protocol = productLine.protocolSelect.selected.value,
                initiatedBy = productLine.initiatedBy.selected.value,
                port = parseInt(productLine.port.value),
                portl = parseInt(productLine.portl.value),
                matches,
                ietf;

            (use === 'fr') ? ietf = 'dst' : ietf = 'src';

            if (!name) {
                return null;
            }
            // debugger;
            if (protocol === 'tcp') {
                let ver;
                if (title === 'internetCommunication') {
                    ver = {
                        [version]: {
                            [`ietf-acldns:${ietf}-dnsname`]: name,
                            "protocol": 6
                        }
                    };
                } else if (title === 'controllersSpecific') {
                    ver = getters.udpTcpIetfMud("my-controller", [null], version, 6);

                } else if (title === 'controllerAccess') {
                    ver = getters.udpTcpIetfMud("controller", name, version, 6);

                } else if (title === 'localCommunication') {
                    ver = getters.udpTcpIetfMud("local-networks", [null], version, 6);

                } else if (title === 'specificType') {
                    ver = getters.udpTcpIetfMud("manufacturer", name, version, 6);

                } else if (title === 'device') {
                    ver = getters.udpTcpIetfMud("same-manufacturer", [null], version, 6);
                }

                let source = null,
                    destination = null;
                if (port) {
                    let namePort = (use === 'to') ? "source-port" : "destination-port";
                    source = {
                        [namePort]: {
                            "operator": "eq",
                            "port": port
                        },
                    }
                }
                if (portl) {
                    let namePort = (use === 'to') ? "destination-port" : "source-port";
                    destination = {
                        [namePort]: {
                            "operator": "eq",
                            "port": portl
                        }
                    }
                }

                let tcp = {
                    "tcp": {...source, ...destination}
                };

                if (initiatedBy === 'thing') {

                    tcp.tcp = Object.assign({}, {'ietf-mud:direction-initiated': "from-device"}, tcp.tcp);
                } else if (initiatedBy === 'remote') {
                    tcp.tcp = Object.assign({}, {'ietf-mud:direction-initiated': "to-device"}, tcp.tcp);
                }
                matches = Object.assign({}, ver, _.isEmpty(tcp.tcp) ? null : tcp);

            } else if (protocol === 'udp') {
                let ver;
                if (title === 'internetCommunication') {
                    ver = {
                        [version]: {
                            [`ietf-acldns:${ietf}-dnsname`]: name,
                            "protocol": 17
                        }
                    };
                }
                else if (title === 'controllersSpecific') {
                    ver = getters.udpTcpIetfMud("my-controller", [null], version, 17);
                }
                else if (title === 'controllerAccess') {
                    ver = getters.udpTcpIetfMud("controller", name, version, 17);

                }
                else if (title === 'localCommunication') {
                    ver = getters.udpTcpIetfMud("local-networks", [null], version, 17);

                } else if (title === 'specificType') {
                    ver = getters.udpTcpIetfMud("manufacturer", name, version, 17);

                } else if (title === 'device') {
                    ver = getters.udpTcpIetfMud("same-manufacturer", [null], version, 17);

                }

                let source = null,
                    destination = null;
                if (port) {
                    let namePort = (use === 'to') ? "source-port" : "destination-port";
                    source = {
                        [namePort]: {
                            "operator": "eq",
                            "port": port
                        },
                    }
                }

                if (portl) {
                    let namePort = (use === 'to') ? "destination-port" : "source-port";
                    destination = {
                        [namePort]: {
                            "operator": "eq",
                            "port": portl
                        }
                    }
                }

                let udp = {
                    "udp": {...source, ...destination}
                };

                matches = Object.assign({}, ver, _.isEmpty(udp.udp) ? null : udp);
            } else {

                let ver;
                if (title === 'controllersSpecific') ver = getters.anyIetfMud('my-controller', [null])
                else if (title === 'internetCommunication') ver = {[version]: {[`ietf-acldns:${ietf}-dnsname`]: name}};
                else if (title === 'controllerAccess') ver = getters.anyIetfMud('controller', name);
                else if (title === 'localCommunication') ver = getters.anyIetfMud('local-networks', [null]);
                else if (title === 'specificType') ver = getters.anyIetfMud('manufacturer', name);
                else if (title === 'device') ver = getters.anyIetfMud('same-manufacturer', [null]);

                matches = Object.assign({}, ver);
            }

            return {
                "name": `${aceName}${index}-${use}dev`,
                "matches": {
                    ...matches
                },
                "actions": {
                    "forwarding": "accept"
                }
            };
        },
        jsonClnames: (state, getters) => (ace) => {
            let arr = [];

            arr = ace.map(ace => {
                return state.product[ace.name].map((value, index) => {
                    return {value: value, index: index, name: ace.name, showNetwork: ace.showNetwork}
                })
            });

            let deviceSelect = [state.product.deviceSelect.selected.value];
            if (state.product.deviceSelect.selected.value === 'both') {
                deviceSelect = ['v4', 'v6']
            }

            return deviceSelect.map((item) => {
                let aceArrTo = [],
                    aceArrFr = [],
                    ip = `ip${item}`;

                aceArrTo.push(...arr.map(list => {
                    return list.map(val => {

                        return getters.ace(state, val.index, `ip${item}`, val.value.name.value, 'to', state.product[val.name], val.showNetwork.name, val.name)
                    })
                }));

                aceArrFr.push(...arr.map(list => {
                    return list.map(val => {
                        return getters.ace(state, val.index, `ip${item}`, val.value.name.value, 'fr', state.product[val.name], val.showNetwork.name, val.name)
                    })
                }));

                return [{
                    "name": `mud-${state.product.random}-${item}to`,
                    "type": `ip${item}-acl-type`,
                    "aces": {
                        "ace": [].concat.apply([], _.filter(aceArrTo, (item) => item[0] !== null))
                    }
                }, {
                    "name": `mud-${state.product.random}-${item}fr`,
                    "type": `ip${item}-acl-type`,
                    "aces": {
                        "ace": [].concat.apply([], _.filter(aceArrFr, (item) => item[0] !== null))
                    }
                }];
            });
        },

        dinamicName: (state) => {
            let deviceSelect = [state.product.deviceSelect.selected.value];
            if (state.product.deviceSelect.selected.value === 'both') {
                deviceSelect = ['v4', 'v6']
            }
            return deviceSelect.map((item) => {
                return {"name": `mud-${state.product.random}-${item}`}
            });
        },

        jsonCreate: (state, getters) => {
            let product = state.product,
                fullName = `${product.mudhost}/${product.deviceType}/${product.mudfilename}/${product.sysDescr}/${state.mudName}`,
                mudFileNameJson = `${fullName}.json`,
                mudFilep7s = `${fullName}.p7s`;
            state.json = {
                "ietf-mud:mud": {
                    "mud-version": 1,
                    "mud-url": `${state.mudUrl}/mudFile/${mudFileNameJson}`,
                    "cache-validity": 48,
                    "is-supported": true,
                    "systeminfo": product.sysDescr,
                    "mud-signature": `${state.mudUrl}/mudFile/${mudFilep7s}`,
                    "from-device-policy": {
                        "access-lists": {
                            "access-list": getters.dinamicName.map((item) => {
                                return {name: item.name + 'fr'}
                            })
                        }
                    },
                    "to-device-policy": {
                        "access-lists": {
                            "access-list": getters.dinamicName.map((item) => {
                                return {name: item.name + 'to'}
                            })
                        }
                    }
                }
            };

            let modified = [];
            let mudFile = {};
            for (let name in product.showNetwork) {
                if (product.showNetwork[name].show) {
                    modified.push({name: name, showNetwork: product.showNetwork[name]});
                    mudFile[name] = product[name]
                }
            }

            let acl = getters.jsonClnames(modified);
            return {
                files: {
                    mudFile: mudFile,
                    manufacturer: product.mudhost, //mudhost
                    deviceType: product.deviceType,
                    model: product.mudfilename, //mudfilename
                    software: product.sysDescr, // sysDescr
                    deviceSelect: product.deviceSelect.selected.value,
                    mudFilePathJson: `${product.mudhost}/${product.deviceType}/${product.mudfilename}/${product.sysDescr}/`,
                    mudFileNameJson: `${state.mudName}.json`,
                },
                json: JSON.stringify(Object.assign(
                    state.json,
                    {
                        "ietf-access-control-list:access-lists": {
                            "acl": [].concat.apply([], acl)
                        }
                    }
                ), undefined, 2)
            }
        },
    },
    mutations: {
        styleHeader: (state, value) => {
            state.styleHeader = value;
        },
        isAdmin: (state, value) => {
            state.user = value;
            state.isAdmin = value.isAdmin;
            state.styleHeader = true;
        },
        removeInput: (state, value) => {
            if (value.name.length > 1) {
                value.name.splice(value.index, 1)
            }

        },
        addInput: (state, name) => {
            name.name.push({
                    name: {value: name.internetHost},
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
                        selected: name.selected,
                        options: [
                            {text: 'Any', value: 'any'},
                            {text: 'TCP', value: 'tcp'},
                            {text: 'UDP', value: 'udp'}
                        ]
                    }
                }
            );
        },
        domainModal: (state, value) => {
            state.domain = value.email.split("@").pop();
            if (value.registration_company) $('#userRegistrationDomainModal').modal('show');
        }
    },
    actions: {
        styleHeader: (context, value) => {
            context.commit('styleHeader', value);
        },
        isAdmin: (context, value) => {
            context.commit('isAdmin', value);
        },
        removeInput: (context, value) => {
            context.commit('removeInput', value);
        },
        addInput: (context, name) => {
            context.commit('addInput', name);
        },
        domainModal: (context, value) => {
            context.commit('domainModal', value);
        }
    }
});
