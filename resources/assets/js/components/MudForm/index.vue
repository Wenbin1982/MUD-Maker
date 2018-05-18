<template>
    <div>
        <form @submit.prevent="handleJson()" autocomplete="off">

            <div class="mud_maker_form">
                <div class="title"><p>Please enter host and model the intended MUD-URL for this device:</p></div>
                <div class="row">
                    <div class="col-md-3">
                        <label>Manufacturer:</label>
                        <input type="text"
                               v-model="product.mudhost"
                               name="manufacturer"
                               class="form-control"
                               :readonly="true"
                               v-validate="'required'"
                               :class="{'input': true, 'is-danger': errors.has('manufacturer') }">

                        <span v-show="errors.has('manufacturer')"
                              class="help is-danger">
                            {{ errors.first('manufacturer') }}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <label>Device type:</label>
                        <input type="text"
                               v-model="product.deviceType"
                               name="device"
                               class="form-control"
                               :readonly="newMudFile"
                               v-validate="'required'"
                               :class="{'input': true, 'is-danger': errors.has('device') }">

                        <span v-show="errors.has('device')"
                              class="help is-danger">
                            {{ errors.first('device') }}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <label>Model:</label>
                        <input type="text"
                               v-model="product.mudfilename"
                               name="model"
                               class="form-control"
                               :readonly="newMudFile"
                               v-validate="'required'"
                               :class="{'input': true, 'is-danger': errors.has('model') }">

                        <span v-show="errors.has('model')"
                              class="help is-danger">
                            {{ errors.first('model') }}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <label>Software version:</label>
                        <input type="text"
                               v-model="product.sysDescr"
                               name="software"
                               class="form-control"
                               :readonly="newMudFile"
                               v-validate="'required'"
                               :class="{'input': true, 'is-danger': errors.has('software') }">

                        <span v-show="errors.has('software')"
                              class="help is-danger">
                            {{ errors.first('software') }}</span>
                    </div>
                </div>
            </div>

            <div class="mud_maker_form">
                <div class="title"><p>How will this device communicate on the network?</p></div>
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingInternetCommunication">
                            <div class="card-header_col">
                                <div class="title_line">
                                    <label class="control control--checkbox">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               v-model="product.showNetwork.internetCommunication.show"
                                               @click="accordion($event)">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <span>Internet communication</span>
                                </div>
                            </div>
                            <div class="card-header_col">
                                <div class="text_line">
                                    <p>Access to cloud services and other specific Internet hosts.</p>
                                </div>
                                <i class="fas fa-angle-left" @click="accordion($event)"></i>
                            </div>
                        </div>
                        <div id="collapseInternetCommunication" class="collapse"
                             aria-labelledby="headingInternetCommunication" data-target="#accordion">
                            <div class="card-body">
                                <p>Create rules below</p>
                                <div class="wrap-row">
                                    <div class="card-body_row" v-for="(col, index) in product.internetCommunication">
                                        <div class="card-body_col">
                                            <div class="inp">
                                                <input type="text"
                                                       class="form-control"
                                                       v-model="col.name.value"
                                                       placeholder="Internet host"
                                                       :name="'internet_communication'+index"
                                                       data-vv-as="internet communication"
                                                       v-validate="{ regex: /[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/ }"
                                                       :class="{'input': true, 'is-danger': errors.has('internet_communication'+index) }">

                                                <div v-show="errors.has('internet_communication'+index)"
                                                     class="help is-danger">
                                                    {{ errors.first('internet_communication'+index) }}
                                                </div>
                                            </div>
                                            <div class="inp"
                                                 v-if="(col.protocolSelect.selected.value === 'tcp' || col.protocolSelect.selected.value === 'udp')">
                                                <div class="inp__col">
                                                    <span>Local Port</span>
                                                    <input type="text"
                                                           :name="'internet_communication_local_port'+index"
                                                           data-vv-as="local port"
                                                           class="form-control"
                                                           v-model="col.portl.value"
                                                           v-validate="portsValidate(col.portl)"
                                                           :class="{'input': true, 'is-danger': errors.has('internet_communication_local_port'+index) }">
                                                    <div v-show="errors.has('internet_communication_local_port'+index)"
                                                         class="help is-danger">
                                                        {{ errors.first('internet_communication_local_port'+index) }}
                                                    </div>
                                                </div>
                                                <div class="inp__col">
                                                    <span>Remote Port</span>
                                                    <input type="text"
                                                           :name="'internet_communication_remote_port'+index"
                                                           data-vv-as="remote port"
                                                           class="form-control"
                                                           v-model="col.port.value"
                                                           v-validate="portsValidate(col.port)"
                                                           :class="{'input': true, 'is-danger': errors.has('internet_communication_remote_port'+index) }">

                                                    <div v-show="errors.has('internet_communication_remote_port'+index)"
                                                         class="help is-danger">
                                                        {{ errors.first('internet_communication_remote_port'+index) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body_col">
                                            <div class="inp inp_sel">
                                                <p>Protocol</p>
                                                <div class="sel">
                                                    <select v-model="col.protocolSelect.selected.value"
                                                            class="form-control">
                                                        <option v-for="option in col.protocolSelect.options"
                                                                :value="option.value">{{ option.text }}
                                                        </option>
                                                    </select>
                                                </div>


                                                <transition name="fade">
                                                    <button type="button" class="add_network"
                                                            v-if="product.internetCommunication.length > 1"
                                                            @click="removeInput({name: product.internetCommunication, index: index})">
                                                        -
                                                    </button>
                                                </transition>
                                                <transition name="slide-fade">
                                                    <button type="button" class="add_network" v-if="index === 0"
                                                            @click="addInput({name: product.internetCommunication, selected:{text: 'Any', value: 'any'}, internetHost: '' })">
                                                        +
                                                    </button>
                                                </transition>
                                            </div>
                                            <div class="inp inp_sel"
                                                 v-if="(col.protocolSelect.selected.value === 'tcp')">
                                                <p> Initiated by</p>
                                                <div class="sel">
                                                    <select v-model="col.initiatedBy.selected.value"
                                                            class="form-control">
                                                        <option v-for="option in col.initiatedBy.options"
                                                                :value="option.value">{{ option.text }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingControllersSpecific">
                            <div class="card-header_col">
                                <div class="title_line">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" class="form-check-input"
                                               v-model="product.showNetwork.controllersSpecific.show"
                                               @click="accordion($event)">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <span>Controllers specific</span>
                                </div>
                            </div>
                            <div class="card-header_col">
                                <div class="text_line">
                                    <p>Access to controllers specific to this device (no need to name a class).</p>
                                </div>
                                <i class="fas fa-angle-left" @click="accordion($event)"></i>
                            </div>
                        </div>
                        <div id="collapseControllersSpecific" class="collapse "
                             aria-labelledby="headingControllersSpecific" data-target="#accordion">
                            <div class="card-body">
                                <p>Create rules below</p>
                                <div class="wrap-row">
                                    <div class="card-body_row" v-for="(col, index) in product.controllersSpecific">
                                        <div class="card-body_col">
                                            <div class="inp">
                                                <input type="text" class="form-control"
                                                       value="(filled in by local admin)" readonly
                                                       placeholder="Internet host"
                                                       pattern="[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$">
                                            </div>
                                            <div class="inp"
                                                 v-if="(col.protocolSelect.selected.value === 'tcp' || col.protocolSelect.selected.value === 'udp')">
                                                <div class="inp__col">
                                                    <span>Local Port</span>
                                                    <input type="text"
                                                           :name="'controllers_specific_local_port'+index"
                                                           data-vv-as="local port"
                                                           class="form-control"
                                                           v-model="col.portl.value"
                                                           v-validate="portsValidate(col.portl)"
                                                           :class="{'input': true, 'is-danger': errors.has('controllers_specific_local_port'+index) }">
                                                    <div v-show="errors.has('controllers_specific_local_port'+index)"
                                                         class="help is-danger">
                                                        {{ errors.first('controllers_specific_local_port'+index) }}
                                                    </div>
                                                </div>
                                                <div class="inp__col">
                                                    <span>Remote Port</span>
                                                    <input type="text"
                                                           :name="'controllers_specific_remote_port'+index"
                                                           data-vv-as="remote port"
                                                           class="form-control"
                                                           v-model="col.port.value"
                                                           v-validate="portsValidate(col.port)"
                                                           :class="{'input': true, 'is-danger': errors.has('controllers_specific_remote_port'+index) }">

                                                    <div v-show="errors.has('controllers_specific_remote_port'+index)"
                                                         class="help is-danger">
                                                        {{ errors.first('controllers_specific_remote_port'+index) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body_col">
                                            <div class="inp inp_sel">
                                                <p>Protocol</p>
                                                <div class="sel">
                                                    <select v-model="col.protocolSelect.selected.value"
                                                            class="form-control">
                                                        <option v-for="option in col.protocolSelect.options"
                                                                :value="option.value"
                                                                :disabled="(index > 0) ? option.value === 'any' : false">
                                                            {{ option.text }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <transition name="fade">
                                                    <button type="button" class="add_network"
                                                            v-if="product.controllersSpecific.length > 1"
                                                            @click="removeInput({name: product.controllersSpecific, index: index})">
                                                        -
                                                    </button>
                                                </transition>
                                                <transition name="slide-fade">
                                                    <button type="button" class="add_network" v-if="index === 0"
                                                            @click="addInput({name:product.controllersSpecific, selected:{text: 'TCP', value: 'tcp'}, internetHost: '(filled in by local admin)'})">
                                                        +
                                                    </button>
                                                </transition>


                                            </div>
                                            <div class="inp inp_sel"
                                                 v-if="(col.protocolSelect.selected.value === 'tcp')">
                                                <p> Initiated by</p>
                                                <div class="sel">
                                                    <select v-model="col.initiatedBy.selected.value"
                                                            class="form-control">
                                                        <option v-for="option in col.initiatedBy.options"
                                                                :value="option.value">{{ option.text }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingControllerAccess">
                            <div class="card-header_col">
                                <div class="title_line">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" class="form-check-input"
                                               v-model="product.showNetwork.controllerAccess.show"
                                               @click="accordion($event)">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <span>Controller access</span>
                                </div>
                            </div>
                            <div class="card-header_col">
                                <div class="text_line">
                                    <p>Access to classes of devices that are known to be controllers</p>
                                </div>
                                <i class="fas fa-angle-left" @click="accordion($event)"></i>
                            </div>
                        </div>
                        <div id="collapseControllerAccess" class="collapse "
                             aria-labelledby="headingControllerAccess"
                             data-target="#accordion">
                            <div class="card-body">
                                <p>Create rules below</p>
                                <div class="wrap-row">
                                    <div class="card-body_row" v-for="(col, index) in product.controllerAccess">
                                        <div class="card-body_col">
                                            <div class="inp">
                                                <input type="url" class="form-control" v-model="col.name.value"
                                                       :name="'controller_access'+index"
                                                       data-vv-as="controller access"
                                                       placeholder="Internet host"
                                                       v-validate="validateUrl"
                                                       :class="{'input': true, 'is-danger': errors.has('controller_access'+index) }">

                                                <div v-show="errors.has('controller_access'+index)"
                                                     class="help is-danger">
                                                    {{ errors.first('controller_access'+index) }}
                                                </div>
                                            </div>
                                            <div class="inp"
                                                 v-if="(col.protocolSelect.selected.value === 'tcp' || col.protocolSelect.selected.value === 'udp')">
                                                <div class="inp__col">
                                                    <span>Local Port</span>
                                                    <input type="text"
                                                           :name="'controller_access_local_port'+index"
                                                           data-vv-as="local port"
                                                           class="form-control"
                                                           v-model="col.portl.value"
                                                           v-validate="portsValidate(col.portl)"
                                                           :class="{'input': true, 'is-danger': errors.has('controller_access_local_port'+index) }">
                                                    <div v-show="errors.has('controller_access_local_port'+index)"
                                                         class="help is-danger">
                                                        {{ errors.first('controller_access_local_port'+index) }}
                                                    </div>
                                                </div>
                                                <div class="inp__col">
                                                    <span>Remote Port</span>
                                                    <input type="text"
                                                           :name="'controller_access_remote_port'+index"
                                                           data-vv-as="remote port"
                                                           class="form-control"
                                                           v-model="col.port.value"
                                                           v-validate="portsValidate(col.port)"
                                                           :class="{'input': true, 'is-danger': errors.has('controller_access_remote_port'+index) }">

                                                    <div v-show="errors.has('controller_access_remote_port'+index)"
                                                         class="help is-danger">
                                                        {{ errors.first('controller_access_remote_port'+index) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body_col">
                                            <div class="inp inp_sel">
                                                <p>Protocol</p>
                                                <div class="sel">
                                                    <select v-model="col.protocolSelect.selected.value"
                                                            class="form-control">
                                                        <option v-for="option in col.protocolSelect.options"
                                                                :value="option.value">{{ option.text }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <transition name="fade">
                                                    <button type="button" class="add_network"
                                                            v-if="product.controllerAccess.length > 1"
                                                            @click="removeInput({name: product.controllerAccess, index: index})">
                                                        -
                                                    </button>
                                                </transition>
                                                <transition name="slide-fade">
                                                    <button type="button" class="add_network" v-if="index === 0"
                                                            @click="addInput({name: product.controllerAccess, selected:{text: 'Any', value: 'any'}, internetHost: ''})">
                                                        +
                                                    </button>
                                                </transition>


                                            </div>
                                            <div class="inp inp_sel"
                                                 v-if="(col.protocolSelect.selected.value === 'tcp')">
                                                <p> Initiated by</p>
                                                <div class="sel">
                                                    <select v-model="col.initiatedBy.selected.value"
                                                            class="form-control">
                                                        <option v-for="option in col.initiatedBy.options"
                                                                :value="option.value">{{ option.text }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingLocalCommunication">
                            <div class="card-header_col">
                                <div class="title_line">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" class="form-check-input"
                                               v-model="product.showNetwork.localCommunication.show"
                                               @click="accordion($event)">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <span>Local communication</span>
                                </div>
                            </div>
                            <div class="card-header_col">
                                <div class="text_line">
                                    <p>Access to/from any local host for specific services (like COAP or HTTP) </p>
                                </div>
                                <i class="fas fa-angle-left" @click="accordion($event)"></i>
                            </div>
                        </div>
                        <div id="collapseLocalCommunication" class="collapse "
                             aria-labelledby="headingLocalCommunication" data-target="#accordion">
                            <div class="card-body">
                                <p>Create rules below</p>
                                <div class="wrap-row">
                                    <div class="card-body_row" v-for="(col, index) in product.localCommunication">
                                        <div class="card-body_col">
                                            <div class="inp">
                                                <input type="text" class="form-control" value="any" readonly
                                                       placeholder="Internet host"
                                                       pattern="[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$">
                                            </div>
                                            <div class="inp"
                                                 v-if="(col.protocolSelect.selected.value === 'tcp' || col.protocolSelect.selected.value === 'udp')">
                                                <div class="inp__col">
                                                    <span>Local Port</span>
                                                    <input type="text"
                                                           :name="'local_communication_local_port'+index"
                                                           data-vv-as="local port"
                                                           class="form-control"
                                                           v-model="col.portl.value"
                                                           v-validate="portsValidate(col.portl)"
                                                           :class="{'input': true, 'is-danger': errors.has('local_communication_local_port'+index) }">
                                                    <div v-show="errors.has('local_communication_local_port'+index)"
                                                         class="help is-danger">
                                                        {{ errors.first('local_communication_local_port'+index) }}
                                                    </div>
                                                </div>
                                                <div class="inp__col">
                                                    <span>Remote Port</span>
                                                    <input type="text"
                                                           :name="'local_communication_remote_port'+index"
                                                           data-vv-as="remote port"
                                                           class="form-control"
                                                           v-model="col.port.value"
                                                           v-validate="portsValidate(col.port)"
                                                           :class="{'input': true, 'is-danger': errors.has('local_communication_remote_port'+index) }">

                                                    <div v-show="errors.has('local_communication_remote_port'+index)"
                                                         class="help is-danger">
                                                        {{ errors.first('local_communication_remote_port'+index) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body_col">
                                            <div class="inp inp_sel">
                                                <p>Protocol</p>
                                                <div class="sel">
                                                    <select v-model="col.protocolSelect.selected.value"
                                                            class="form-control">
                                                        <option v-for="option in col.protocolSelect.options"
                                                                :value="option.value"
                                                                :disabled="(index > 0) ? option.value === 'any' : false">
                                                            {{ option.text }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <transition name="fade">
                                                    <button type="button" class="add_network"
                                                            v-if="product.localCommunication.length > 1"
                                                            @click="removeInput({name: product.localCommunication, index: index})">
                                                        -
                                                    </button>
                                                </transition>
                                                <transition name="slide-fade">
                                                    <button type="button" class="add_network" v-if="index === 0"
                                                            @click="addInput({name:product.localCommunication, selected:{text: 'TCP', value: 'tcp'}, internetHost: 'any'})">
                                                        +
                                                    </button>
                                                </transition>
                                            </div>
                                            <div class="inp inp_sel"
                                                 v-if="(col.protocolSelect.selected.value === 'tcp')">
                                                <p> Initiated by</p>
                                                <div class="sel">
                                                    <select v-model="col.initiatedBy.selected.value"
                                                            class="form-control">
                                                        <option v-for="option in col.initiatedBy.options"
                                                                :value="option.value">{{ option.text }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingSpecificTypes">
                            <div class="card-header_col">
                                <div class="title_line">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" class="form-check-input"
                                               v-model="product.showNetwork.specificType.show"
                                               @click="accordion($event)">
                                        <div class="control__indicator"></div>
                                    </label>

                                    <span>Specific types of devices</span>
                                </div>
                            </div>
                            <div class="card-header_col">
                                <div class="text_line">
                                    <p>Access to classes of devices that are identified by their MUD URL</p>
                                </div>
                                <i class="fas fa-angle-left" @click="accordion($event)"></i>
                            </div>
                        </div>
                        <div id="collapseSpecificTypes" class="collapse" aria-labelledby="headingSpecificTypes"
                             data-target="#accordion">
                            <div class="card-body">
                                <p>Create rules below</p>
                                <div class="wrap-row">
                                    <div class="card-body_row" v-for="(col, index) in product.specificType">
                                        <div class="card-body_col">
                                            <div class="inp">
                                                <input type="text" class="form-control" v-model="col.name.value"
                                                       :name="'specific_types'+index"
                                                       data-vv-as="specific types"
                                                       placeholder="Internet host"
                                                       v-validate="{ regex: /[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/ }"
                                                       :class="{'input': true, 'is-danger': errors.has('specific_types'+index) }">

                                                <div v-show="errors.has('specific_types'+index)"
                                                     class="help is-danger">
                                                    {{ errors.first('specific_types'+index) }}
                                                </div>
                                            </div>
                                            <div class="inp"
                                                 v-if="(col.protocolSelect.selected.value === 'tcp' || col.protocolSelect.selected.value === 'udp')">
                                                <div class="inp__col">
                                                    <span>Local Port</span>
                                                    <input type="text"
                                                           :name="'specific_types_local_port'+index"
                                                           data-vv-as="local port"
                                                           class="form-control"
                                                           v-model="col.portl.value"
                                                           v-validate="portsValidate(col.portl)"
                                                           :class="{'input': true, 'is-danger': errors.has('specific_types_local_port'+index) }">
                                                    <div v-show="errors.has('specific_types_local_port'+index)"
                                                         class="help is-danger">
                                                        {{ errors.first('specific_types_local_port'+index) }}
                                                    </div>
                                                </div>
                                                <div class="inp__col">
                                                    <span>Remote Port</span>
                                                    <input type="text"
                                                           :name="'specific_types_remote_port'+index"
                                                           data-vv-as="remote port"
                                                           class="form-control"
                                                           v-model="col.port.value"
                                                           v-validate="portsValidate(col.port)"
                                                           :class="{'input': true, 'is-danger': errors.has('specific_types_remote_port'+index) }">

                                                    <div v-show="errors.has('specific_types_remote_port'+index)"
                                                         class="help is-danger">
                                                        {{ errors.first('specific_types_remote_port'+index) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body_col">
                                            <div class="inp inp_sel">
                                                <p>Protocol</p>
                                                <div class="sel">
                                                    <select v-model="col.protocolSelect.selected.value"
                                                            class="form-control">
                                                        <option v-for="option in col.protocolSelect.options"
                                                                :value="option.value">{{ option.text }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <transition name="fade">
                                                    <button type="button" class="add_network"
                                                            v-if="product.specificType.length > 1"
                                                            @click="removeInput({name: product.specificType, index: index})">
                                                        -
                                                    </button>
                                                </transition>
                                                <transition name="slide-fade">
                                                    <button type="button" class="add_network" v-if="index === 0"
                                                            @click="addInput({name: product.specificType, selected:{text: 'Any', value: 'any'}, internetHost: ''})">
                                                        +
                                                    </button>
                                                </transition>

                                            </div>
                                            <div class="inp inp_sel"
                                                 v-if="(col.protocolSelect.selected.value === 'tcp')">
                                                <p> Initiated by</p>
                                                <div class="sel">
                                                    <select v-model="col.initiatedBy.selected.value"
                                                            class="form-control">
                                                        <option v-for="option in col.initiatedBy.options"
                                                                :value="option.value">{{ option.text }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingDevices">
                            <div class="card-header_col">
                                <div class="title_line">
                                    <label class="control control--checkbox">
                                        <input type="checkbox"
                                               v-model="product.showNetwork.device.show"
                                               @click="accordion($event)"
                                               class="form-check-input">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <span>Devices</span>
                                </div>
                            </div>
                            <div class="card-header_col">
                                <div class="text_line">
                                    <p>Access to devices to/from the same manufacturer </p>
                                </div>
                                <i class="fas fa-angle-left" @click="accordion($event)"></i>
                            </div>
                        </div>
                        <div id="collapseDevices" class="collapse " aria-labelledby="headingDevices"
                             data-target="#accordion">
                            <div class="card-body">
                                <p>Create rules below</p>
                                <div class="wrap-row">
                                    <div class="card-body_row" v-for="(col, index) in product.device">
                                        <div class="card-body_col">
                                            <div class="inp">
                                                <input type="text" class="form-control" value="(filled in by system)"
                                                       readonly placeholder="Internet host"
                                                       pattern="[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$">
                                            </div>
                                            <div class="inp"
                                                 v-if="(col.protocolSelect.selected.value === 'tcp' || col.protocolSelect.selected.value === 'udp')">
                                                <div class="inp__col">
                                                    <span>Local Port</span>
                                                    <input type="text"
                                                           :name="'devices_local_port'+index"
                                                           data-vv-as="local port"
                                                           class="form-control"
                                                           v-model="col.portl.value"
                                                           v-validate="portsValidate(col.portl)"
                                                           :class="{'input': true, 'is-danger': errors.has('devices_local_port'+index) }">
                                                    <div v-show="errors.has('devices_local_port'+index)"
                                                         class="help is-danger">
                                                        {{ errors.first('devices_local_port'+index) }}
                                                    </div>
                                                </div>
                                                <div class="inp__col">
                                                    <span>Remote Port</span>
                                                    <input type="text"
                                                           :name="'devices_remote_port'+index"
                                                           data-vv-as="local remote"
                                                           class="form-control"
                                                           v-model="col.port.value"
                                                           v-validate="portsValidate(col.port)"
                                                           :class="{'input': true, 'is-danger': errors.has('devices_remote_port'+index) }">

                                                    <div v-show="errors.has('devices_remote_port'+index)"
                                                         class="help is-danger">
                                                        {{ errors.first('devices_remote_port'+index) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body_col">
                                            <div class="inp inp_sel">
                                                <p>Protocol</p>
                                                <div class="sel">
                                                    <select v-model="col.protocolSelect.selected.value"
                                                            class="form-control">
                                                        <option v-for="option in col.protocolSelect.options"
                                                                :value="option.value"
                                                                :disabled="(index > 0) ? option.value === 'any' : false">
                                                            {{ option.text }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <transition name="fade">
                                                    <button type="button" class="add_network"
                                                            v-if="product.device.length > 1"
                                                            @click="removeInput({name: product.device, index: index})">-
                                                    </button>
                                                </transition>
                                                <transition name="slide-fade">
                                                    <button type="button" class="add_network" v-if="index === 0"
                                                            @click="addInput({name:product.device, selected:{text: 'TCP', value: 'tcp'}, internetHost: '(filled in by system)'})">
                                                        +
                                                    </button>
                                                </transition>
                                            </div>
                                            <div class="inp inp_sel"
                                                 v-if="(col.protocolSelect.selected.value === 'tcp')">
                                                <p> Initiated by</p>
                                                <div class="sel">
                                                    <select v-model="col.initiatedBy.selected.value"
                                                            class="form-control">
                                                        <option v-for="option in col.initiatedBy.options"
                                                                :value="option.value">{{ option.text }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mud_maker_speaks">
                <p>This device speaks</p>
                <div class="sel">
                    <select v-model="product.deviceSelect.selected.value" class="form-control">
                        <option v-for="option in product.deviceSelect.options"
                                :value="option.value"
                                :selected="(option.text === product.deviceSelect.selected.text) ? option.text : ''">
                            {{ option.text }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="mud_maker_btn">
                <button class="btn btn_dark" type="reset" value="reset" @click="$emit('reset')" :style="resetStyle"
                        ref="resetBtn">Reset
                </button>
                <button class="btn btn_green" type="submit">Submit</button>
            </div>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';
    import {mapGetters, mapActions} from 'vuex';
    import _ from 'lodash';

    export default {
        data: function () {
            return {
                validateUrl: { url: true, regex: /^(http|https):/ },
                portsValidate: (port) => (port.value !== 'any') ? {
                    min_value: 1000,
                    max_value: 65000,
                    required: true
                } : {
                    alpha_num: true,
                    required: true,
                    regex: /([0-9]{1,5}|any)/
                }
            }
        },
        computed: {
            ...mapGetters([
                'dinamicName',
                'jsonClnames',
                'jsonCreate'
            ])
        },
        props: ['product', 'newMudFile', 'resetStyle', 'mudRoute'],
        created() {

        },
        methods: {
            ...mapActions([
                'createJson',
                'removeInput',
                'addInput'
            ]),
            handleJson() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.jsonView = this.$store.getters.jsonCreate.json;
                        let obj = {
                            jsonId: (this.mudRoute === 'store') ? this.$route.params.userId : this.$route.params,
                            jsonData: this.$store.getters.jsonCreate
                        };
                        axios
                            .post(route(this.mudRoute), obj)
                            .then(({data}) => {
                                this.$router.push({
                                    path: `/show/${data}`
                                });
                            })
                            .catch(err => {

                            });
                    } else {
                        $('html, body').animate({
                            scrollTop: $('#app').offset().top
                        }, 500)
                    }

                });


            },
            accordion(event) {
                let inp = $(event.target).closest('.card');
                if ($(event.target).attr('type') === "checkbox") {
                    if ($(event.target).is(':checked')) {
                        inp.addClass('active').find('.collapse').collapse('show')
                    }
                } else {
                    inp.toggleClass('active').find('.collapse').collapse('toggle');
                }
            },
        }
    }
</script>
